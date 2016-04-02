<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Project extends Model
{
	const STATUS_NIETBEGONNEN = 0;
	const STATUS_OFFERTEVERSTUURD = 1;
	const STATUS_ACTIEF = 2;
	const STATUS_FACTUURVERSTUURD = 3;
	const STATUS_AFGEROND = 4;
	const STATUS_OFFERTEAFGEWEZEN = 5;
	
	/**
	 * Cache variable to store total working hours booked on this project 
	 * @var int $totalWorkingHours
	 */
	protected $totalWorkingHours;
	
	/**
	 * Cache variable to store the total amount of money booked on this project
	 * @var int
	 */
	protected $totalAmount;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projecten';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['naam', 'opmerkingen', 'status', 'uurtarief', 'relatie_id', 'post_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }    	
    public function Post()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }
    public function WorkingHours() 
    {
    	return $this->hasMany('\Weboffice\Models\WorkingHour', 'project_id');
    }
	public function Finances()
	{
		return $this->hasMany('\Weboffice\Models\ProjectFinance', 'project_id');
	}
	public function StatementLines()
	{
		return $this->belongsToMany('\Weboffice\Models\StatementLine', 'project_financieen', 'project_id', 'boeking_deel_id');
	}

	/**
	 * Scope a query to only include active projects (i.e. not closed)
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeActive($query)
	{
		return $query->whereNotIn('status', [ self::STATUS_AFGEROND, self::STATUS_OFFERTEAFGEWEZEN ] );
	}

    /**
     * Accessor for amount
     */
    public function getUurtariefAttribute($value) {
    	return round($value) / 100;
    }
    
    /**
     * Mutator for amount
     */
    public function setUurtariefAttribute($amount) {
    	$this->attributes['uurtarief' ] = $amount * 100;
    }
    
    public function getStatus() {
    	switch($this->status) {
    		case self::STATUS_NIETBEGONNEN: 	return "Not started";
    		case self::STATUS_OFFERTEVERSTUURD:	return "Quote sent";
    		case self::STATUS_ACTIEF:			return "Active";
    		case self::STATUS_FACTUURVERSTUURD:	return "Invoice sent";
    		case self::STATUS_AFGEROND:			return "Finished";
    		case self::STATUS_OFFERTEAFGEWEZEN: return "Quote rejected";
    		default:							return "Unknown";
    	}
    }
    
    /**
     * Returns the total amount of working hours for this project
     */
    public function getTotalWorkingHours() {
    	if(is_null($this->totalWorkingHours)) {
    		$this->totalWorkingHours = $this->calculateTotalWorkingHours();
    	}
    	return $this->totalWorkingHours;
    }
    
    /**
     * Returns the total amount of money for this project
     */
    public function getTotalRevenue() {
    	if(is_null($this->totalAmount)) {
    		$this->totalAmount = $this->calculateTotalRevenue();
    	}
    	return $this->totalAmount;
    }
    
    /**
     * Determines whether it is relevent to show a revenue per hour for this project
     */
    public function hasRevenuePerHour() {
    	return $this->getTotalRevenue() > 0 && $this->getTotalWorkingHours() > 0;
    }

    /**
     * Returns the total revenue per hour worked
     */
    public function getRevenuePerHour() {
    	if($this->getTotalWorkingHours() > 0) {
    		return $this->getTotalRevenue() / $this->getTotalWorkingHours();
    	} else {
    		return null;
    	}
    }
    
    /**
     * Calculates the total amount of working hours
     * @return float
     */
    protected function calculateTotalWorkingHours() {
    	$sum = 0;
    
    	// We cannot use a database sum, as duration is not actually stored
    	foreach( $this->WorkingHours as $workingHour ) {
    		$sum += $workingHour->durationInMinutes;
    	}
    
    	return $sum / 60;
    }
    
    /**
     * Calculates the total amount of money
     * @return float
     */
    protected function calculateTotalRevenue() {
    	$sum = 0;
    
    	$stats = $this->StatementLines()
    				->select('credit', DB::raw('SUM(bedrag) / 100 as total'))
	    			->groupBy('credit')
    				->get();
    	
    	foreach($stats as $stat) {
    		$multiplier = $stat->credit ? -1 : 1;
    		$sum += $multiplier * $stat->total;
    	}
    	
    	// As revenue is put on the credit side, and the credit side is negative,
    	// we should negate the sum    	
    	return -$sum;
    }
    
    

}
