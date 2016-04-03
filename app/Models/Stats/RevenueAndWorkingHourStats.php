<?php
namespace Weboffice\Models\Stats;

/**
 * Handles revenue statistics
 * @author robert
 *
 */
trait RevenueAndWorkingHourStats {
	use RevenueStats;
	use WorkingHourStats;
	
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
}


