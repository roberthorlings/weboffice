<?php
namespace Weboffice\Models\Stats;

/**
 * Handles working hours statistics
 * @author robert
 *
 */
trait WorkingHourStats {
	/**
	 * Cache variable to store total working hours booked on this project 
	 * @var int $totalWorkingHours
	 */
	protected $totalWorkingHours;
	
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
    
	
}


