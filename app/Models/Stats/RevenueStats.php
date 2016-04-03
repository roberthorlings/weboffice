<?php
namespace Weboffice\Models\Stats;

use DB;

/**
 * Handles revenue statistics
 * @author robert
 *
 */
trait RevenueStats {
	/**
	 * Cache variable to store the total amount of money booked on this project
	 * @var int
	 */
	protected $totalRevenue;
	
	/**
	 * Returns the total amount of money for this project
	 */
	public function getTotalRevenue() {
		if(is_null($this->totalAmount)) {
			$this->totalRevenue = $this->calculateTotalRevenue();
		}
		return $this->totalRevenue;
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


