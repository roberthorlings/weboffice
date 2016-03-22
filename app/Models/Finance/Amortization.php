<?php

namespace Weboffice\Models\Finance;

use Weboffice\Models\Asset;
use Weboffice\Models\Statement;
use Weboffice\Models\StatementLine;
use Carbon\Carbon;

/**
 * Represents the amortization for a given asset, at the end of the current month
 * @author robert
 *
 */
class Amortization
{
	/**
	 * @var Asset $asset
	 */
	protected $asset;
	
	/**
	 * Cache property for the amount already amortized
	 * @var unknown $amountAmortized
	 */
	protected $amountAmortized = null;
    
	/**
	 * Default constructor
	 * @param Asset $asset
	 */
	public function __construct(Asset $asset) {
		$this->asset = $asset;
	}
	
    /**
     * Returns the amount that has already been amortized. This will include
     * all amortization in the current month
     * @return float
     */
    public function getAmountAlreadyAmortized() {
    	if(is_null($this->amountAmortized)) {
	    	$endOfMonth = Carbon::now()->endOfMonth();
	    	
	    	// Loop through the statements and check which statements to take into account
	    	$amount = 0;
	    	$relevantStatements = $this->asset->Statements()
	    		->with('StatementLines')
	    		->where('omschrijving', 'like', 'Afschrijving%')
	    		->where('datum', '<=', $endOfMonth)
	    		->get();
	    	
	    	foreach( $relevantStatements as $statement) {
	    		// Check if the statement is actually about the amortization (i.e. the #statementlines 
	    		// is correct and the description is about amortization
	    		if(count($statement->StatementLines) == 2) {
	    			$amount += $statement->StatementLines[1]->bedrag;
	    		}
	    	}
	    	
	    	$this->amountAmortized = $amount;
    	} 
    	
    	return $this->amountAmortized;
    }
    
    /**
     * Returns the amount that is still to be amortized
     * @return number
     */
    public function getAmountStillToAmortize() {
    	return $this->asset->bedrag - $this->asset->restwaarde - $this->getAmountAlreadyAmortized();
    }
    
    /**
     * Returns the amount that is amortized every period
     * @return float
     */
    public function getAmount() {
    	$periodsToAmortize = $this->getPeriodsToAmortize();
    	
    	if( $periodsToAmortize == 0 ) {
    		return round( ($this->asset->bedrag - $this->asset->restwaarde) / $this->asset->afschrijvingsduur, 2 );
    	} else {
    		return round( $this->getAmountStillToAmortize() / $this->getPeriodsToAmortize(), 2 );
    	}
    }
    
    /**
     * Returns a textual representatino of the amortization period
     * @return string
     */
    public function getPeriodDescription() {
    	switch($this->asset->afschrijvingsperiode) {
    		case 1: return 'month';
    		case 3: return 'quarter';
    		case 6: return 'half year';
    		case 12: return 'year';
    		default: return $this->asset->afschrijvingsperiode . ' months';
    	}
    }
    
    /**
     * Returns the number of periods that have been amortized
     */
    public function getPeriodsAmortized() {
    	$endOfMonth = Carbon::now()->endOfMonth();
    	// As the amortization may not have been booked, 
    	// we just count the number of statements
    	return $this->asset->Statements()
	    	->where('omschrijving', 'like', 'Afschrijving%')
	    	->where('datum', '<=', $endOfMonth)
	    	->count();    
    }
    
    /**
     * Returns the number of periods still to amortize
     * @return number
     */
    public function getPeriodsToAmortize() {
    	return $this->asset->afschrijvingsduur - $this->getPeriodsAmortized();
    }
    

    /**
     * Returns the current value of this asset in the budget
     * @return float
     */
    public function getCurrentValue() {
    	return $this->asset->bedrag - $this->getAmountAlreadyAmortized();
    }    

    /**
     * Checks whether the current amortization has finished
     */
    public function isFinished() {
    	return $this->getPeriodsToAmortize() == 0;
    }
    

    /**
     * Returns an amortization statement with the given date and amount
     * @param unknown $date
     * @param unknown $amount
     */
    public function getStatement(Carbon $date, $amount) {
    	$statement = new Statement(['datum' => $date, 'omschrijving' => 'Afschrijving ' . $this->asset->omschrijving, 'activum_id' => $this->asset->id ]);
    	 
    	$statement->StatementLines->add(new StatementLine(['bedrag' => $amount, 'credit' => 0, 'post_id' => $this->asset->post_kosten ]));
    	$statement->StatementLines->add(new StatementLine(['bedrag' => $amount, 'credit' => 1, 'post_id' => $this->asset->post_afschrijving ]));
    	 
    	return $statement;
    }
    
    /**
     * Create statements to perform the actual amortization. All amortization up
     * to the current month, will be kept
     */
    public function book() {
    	// First delete all amortization bookings from the next month
    	Statement::where('activum_id', $this->asset->id)
	    	->where('datum', '>', Carbon::now()->endOfMonth() )
			->where('omschrijving', 'like', 'Afschrijving%')
	    	->delete();
    	 
    	// Now start amortizing. Due to rounding, the actual amount that is amortized may change
    	$amountToAmortize = $this->getAmountStillToAmortize();
    	$periodsToAmortize = $this->getPeriodsToAmortize();
    	
    	// The date to start amortization depends on whether amortization has been booked before. 
    	// If so, continue with that amortization. Otherwise, start with the begin_afschrijving date 
    	// if it is in the future, otherwise start next month.
    	$lastBooking = $this->getLastAmortizationBooking();
    	
    	if( $lastBooking ) {
    		$date = $lastBooking->datum->addMonths($this->asset->afschrijvingsperiode);
    	} else {
    		$date = $this->asset->begin_afschrijving;
    	}
    	
    	// If a date in the past is selected, use the start of next month
    	if( !$date || !$date->isFuture() ) {
    		$date = Carbon::now()->addMonth()->startOfMonth();
    	}
    	
    	// Save amortization bookings
    	for( $period = $periodsToAmortize; $period > 0; $period-- ) {
    		// Determine the amount to amortize
    		$amountToAmortizeThisPeriod = round( $amountToAmortize / $period, 2);
    		
    		// Store statement booking
    		$this->getStatement($date, $amountToAmortizeThisPeriod)->saveCascaded();
    		
    		// Update values
    		$amountToAmortize -= $amountToAmortizeThisPeriod;
    		$date->addMonth($this->asset->afschrijvingsperiode);
    	}
    }
    
    /**
     * Returns the last amortization booking, if any
     */
    public function getLastAmortizationBooking() {
    	$endOfMonth = Carbon::now()->endOfMonth();
    	
    	return $this->asset->Statements()
    		->where('datum', '<=', $endOfMonth)
    		->where('omschrijving', 'like', 'Afschrijving%')
    		->orderBy('datum', 'desc')
    		->first();
    }
    
}
