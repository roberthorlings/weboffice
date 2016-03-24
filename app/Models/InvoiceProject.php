<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use AppConfig;

class InvoiceProject extends Model
{
	/**
	 * Cache variable to store the total amount of working hours
	 * @var unknown
	 */
	protected $totalWorkingHours = null;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_invoice';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['invoice_id', 'project_id', 'start', 'end', 'hours_overview_type'];

    protected $dates = [ 'start', 'end' ];
    
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Models\Project', 'project_id');
    }    	
    
    public function Invoice()
    {
    	return $this->hasMany('\Weboffice\Models\Invoice', 'invoice_id');
    }
    
    /**
     * WorkingHours belonging to this invoice project line
     */
    public function WorkingHours() 
    {
    	return $this->Project->WorkingHours()->whereBetween('datum', [$this->start, $this->end]);
    }
    
    /**
     * Get a specific attribute for use in forms. Dates and times have to be formatted properly
     *
     * @return string
     */
    public function getFormValue($field)
    {
    	switch($field) {
    		case 'start':
    		case 'end':
    			return $this->{$field} ? $this->{$field}->format('Y-m-d') : null;
    		default:
    			return $this->{$field};
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
     * Returns the hourly rate for this project
     */
    public function getHourlyRate() {
    	return $this->Project->uurtarief ?: 0;
    }
    
	/**
	 * Returns the total amount to put on the invoice for this project
	 */
    public function getTotalAmount() {
    	return $this->getTotalWorkingHours() * $this->getHourlyRate();
    }
    
    /**
     * Calculates the total amount of working hours
     */
    protected function calculateTotalWorkingHours() {
    	return array_sum(
    		array_map(
    			function($workingHour) { return $workingHour->duration; },
    			$this->WorkingHours()->all()
    		)
    	);
    } 
}
