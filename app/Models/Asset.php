<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use Weboffice\Models\Finance\Amortization;
use AppConfig;
use Carbon\Carbon;

class Asset extends Model
{
	/**
	 * Cache property to store the amortization object
	 * @var Amortization|null $amortization
	 */
	protected $amortization = null;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activa';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['omschrijving', 'aanschafdatum', 'begin_afschrijving', 'bedrag', 'restwaarde', 'afschrijvingsduur', 'afschrijvingsperiode', 'post_investering', 'post_afschrijving', 'post_kosten'];

    protected $dates = [ 'aanschafdatum', 'begin_afschrijving' ];
    
    /**
     * Accessor for amount
     */
    public function getBedragAttribute($value) {
    	return round($value) / 100;
    }
    
    /**
     * Mutator for amount
     */
    public function setBedragAttribute($amount) {
    	$this->attributes['bedrag' ] = $amount * 100;
    }
    
    /**
     * Accessor for rest
     */
    public function getRestwaardeAttribute($value) {
    	return round($value) / 100;
    }
    
    /**
     * Mutator for rest
     */
    public function setRestwaardeAttribute($amount) {
    	$this->attributes['restwaarde' ] = $amount * 100;
    }
    

    /**
     * Get a specific attribute for use in forms. Dates and times have to be formatted properly
     *
     * @return string
     */
    public function getFormValue($field)
    {
    	switch($field) {
    		case 'aanschafdatum':
    		case 'begin_afschrijving':
    			return $this->{$field}->format('Y-m-d');
    		default:
    			return $this->{$field};
    	}
    }   
    
    public function Statements() 
    {
    	return $this->hasMany('\Weboffice\Models\Statement', 'activum_id');
    }
    
    public function PostInvestering()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_investering');
    }    	
    public function PostAfschrijving()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_afschrijving');
    }    	
    public function PostKosten()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_kosten');
    }
    
    /**
     * Returns an Amortization object with information about the amortization of this asset
     */
    public function amortization() {
    	if(!$this->amortization) {
    		$this->amortization = new Amortization($this);
    	}
    	
    	return $this->amortization;
    }

    /**
     * Returns a statement that is or can be saved as investment. If investment has been booked, 
     * the existing statement will be returned
     */
    public function getInvestmentStatement() {
    	return $this->getExistingInvestmentStatement() || $this->getNewInvestmentStatement();
    }
    
    /**
     * Returns the existing investement statement, or null if investment has not been booked yet
     * @return Statement|NULL
     */
    public function getExistingInvestmentStatement() {
    	// Loop through the statements and check if a statement is already booked
    	$amount = 0;
    	$relevantStatements = $this->Statements()->with('StatementLines')->where('datum', '=', $this->aanschafdatum)->get();
    	foreach( $relevantStatements as $statement) {
    		// Check if the statement is actually about the investment
    		if(preg_match('/^Investering/i', $statement->omschrijving)) {
    			return $statement;
    		}
    	}
    	 
    	return null;
    }
    
    /**
     * Returns a statement that can be saved as investment
     */
    public function getNewInvestmentStatement($description = null) {
    	$statement = new Statement(['datum' => $this->aanschafdatum, 'omschrijving' => 'Investering ' . $this->omschrijving, 'opmerkingen' => $description, 'activum_id' => $this->id ]);

    	$vatPercentage = AppConfig::get('btwPercentage');
    	$statement->StatementLines->add(new StatementLine(['bedrag' => $this->bedrag, 'credit' => 0, 'post_id' => $this->post_investering ]));    	
    	$statement->StatementLines->add(new StatementLine(['bedrag' => $this->bedrag * ( $vatPercentage / 100 ), 'credit' => 0, 'post_id' => AppConfig::get('postTeVorderenBTW') ]));    	
    	$statement->StatementLines->add(new StatementLine(['bedrag' => $this->bedrag * ( 1 + $vatPercentage / 100 ), 'credit' => 1, 'post_id' => AppConfig::get('postCrediteuren') ]));
    	
    	return $statement;
    }
    
    /**
     * Returns a statement that can be saved as amortization
     */
    public function getAmortizationStatement() {
    	return $this->amortization()->getStatement($this->begin_afschrijving, $this->amortization()->getAmount());
    }
    
    /**
     * Checks whether the investment for this asset has been booked already
     */
    public function isInvestmentBooked() {
    	return !is_null($this->getExistingInvestmentStatement());
    }
    
    /**
     * Books the investment for the current asset
     * @param unknown $description
     */
    public function bookInvestment($description) {
    	$this->getNewInvestmentStatement($description)->saveCascaded();
    }
}
