<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Saldo extends Model
{
	/**
	 * Cache variable to store open amount, once calculated
	 * @var unknown $openAmount
	 */
	protected $openAmount = null;

	/**
	 * Cache variable to store start date, once calculated
	 * @var unknown $openAmount
	 */
	protected $startDate = null;
	
	/**
	 * Cache variable to store end date once calculated
	 * @var unknown $openAmount
	 */
	protected $endDate = null;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saldos';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['omschrijving', 'relatie_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }
    
    /**
     * Associated statement lines
     */
    public function StatementLines() {
    	return $this->hasMany('\Weboffice\Models\StatementLine', 'saldo_id');
    }
    
    /**
     * Returns the amount that is still open for this saldo
     */
    public function getOpenAmount() {
    	if( is_null($this->openAmount) ) {
    		$this->calculateOpenAmount();
    	}
    	
    	return $this->openAmount;
    }
    
    /**
     * Checks whether this saldo is still open
     * @return boolean
     */
    public function isOpen() {
    	return abs($this->getOpenAmount()) > 0.005;
    }
    
    /**
     * Returns the start date for the saldo, i.e. the date of the first transactionline
     */
    public function getStartDate() {
    	if(is_null($this->startDate)) {
    		$this->calculateStartDate();
    	}
    	return $this->startDate;
    }
    
    /**
     * Returns the end date for the saldo, i.e. the date of the 
     * last transactionline or null if the saldo is still open
     */
    public function getEndDate() {
    	if( $this->isOpen() ) {
    		return null;
    	} else {
    		if(is_null($this->endDate)) {
    			$this->calculateEndDate();
    		}
    		return $this->endDate;
    	}
    }
    
    /**
     * Scope a query to only include open saldos.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
    	return $query->where(DB::raw('0'), '!=', function($subquery) {
    		return $subquery
    			->from('boeking_delen')
    			->where( 'boeking_delen.saldo_id', '=', DB::raw('saldos.id') )
    			->select( DB::raw( 'sum(if(boeking_delen.credit = 0, boeking_delen.bedrag, -boeking_delen.bedrag)) as total' ) );
    	});
    }
    
    /**
     * Calculates the open amount for this saldo
     */
    protected function calculateOpenAmount() {
    	$sum = 0;
    	foreach( $this->StatementLines as $line ) {
    		$sum += $line->getSignedAmount();
    	}
    	$this->openAmount = $sum;
    }

    /**
     * Calculates the start date for this saldo
     */
    protected function calculateStartDate() {
    	$startDate = null;
    	foreach( $this->StatementLines as $line ) {
    		if( !$startDate || $startDate->gt($line->Statement->datum)) {
    			$startDate = $line->Statement->datum; 
    		}
    	}
    	return $this->startDate = $startDate;
    }
    
    /**
     * Calculates the end date for this saldo
     */
    protected function calculateEndDate() {
    	$endDate = null;
    	foreach( $this->StatementLines as $line ) {
    		if( !$endDate || $endDate->lt($line->Statement->datum)) {
    			$endDate = $line->Statement->datum;
    		}
    	}
    	return $this->endDate = $endDate;
    }
    
    public function totalSide() {
    	return $this->totalAmount < 0 ? 'credit' : 'debet';
    }    
    
}
