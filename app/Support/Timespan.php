<?php
namespace Weboffice\Support;

use Carbon\Carbon;
class Timespan {
	/**
	 * @var Carbon $start
	 */
	protected $start;
	
	/**
	 * @var Carbon $end
	 */
	protected $end;
	
	public function __construct(Carbon $start, Carbon $end) {
		$this->start = $start;
		$this->end = $end;
	}
	
	/**
	 * Returns a human readable string for the current timespan
	 */
	public function __toString() {
		return "" . $this->getDescription();
	}
	
	/**
	 * Returns a human readable string for the current timespan
	 */
	public function getDescription() {
		// Some checks which are needed multiple times
		
		// Check whether the timespan handles full months (starts at the first of a 
		// month and ends at the last of a month)
		$fullMonth = (
			$this->start->isSameDay($this->start->copy()->startOfMonth()) &&
			$this->end->isSameDay($this->end->copy()->endOfMonth())
		);
		
		// Check whether the start and end are within the same year
		$withinAYear = ($this->start->year == $this->end->year);
			
		// If we handle full months, we don't have to take into account the day.
		// If not, we cannot simplify the timespan any more
		if($fullMonth) {
			if($withinAYear) {
				// Special case: the full year
				if($this->start->month == 1 && $this->end->month == 12) {
					return $this->start->year;
				}
				
				// Special case: a single month
				if($this->start->month == $this->end->month) {
					return strtolower($this->start->formatLocalized("%B %Y"));
				}
				
				// Special case: a specific quarter
				if($this->end->month - $this->start->month == 2) {
					switch($this->start->month) {
						case 1:  return 'Q1 ' . $this->start->year;
						case 4:  return 'Q2 ' . $this->start->year;
						case 7:  return 'Q3 ' . $this->start->year;
						case 10: return 'Q4 ' . $this->start->year;
					}
				}
				
				// By default, return the month names
				return strtolower(
					$this->start->formatLocalized("%B") . ' - ' . $this->end->formatLocalized("%B %Y")
				);				
			} else { // Not within the same year
				return strtolower(
					$this->start->formatLocalized("%B %Y") . ' - ' . $this->end->formatLocalized("%B %Y")
				);				
			}
		} else {	// no full month
			// If the dates are in the same year, we only have to return the year once
			if($withinAYear) {
				return $this->start->format( 'd-m' ) . ' - ' . $this->end->format( 'd-m Y' );
			} else {
				return $this->start->format( 'd-m-Y' ) . ' - ' . $this->end->format( 'd-m-Y' );
			}
		}
	}
	
	/**
	 * Static creator to be able to easily work with the timespan
	 * @param unknown $start
	 * @param unknown $end
	 */
	public static function create($start, $end) {
		return new self($start, $end);
	}
}