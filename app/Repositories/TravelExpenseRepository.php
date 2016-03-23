<?php
namespace Weboffice\Repositories;

use Weboffice\Models\TravelExpense;
use Illuminate\Database\Eloquent\Builder;
use DB;

class TravelExpenseRepository {
	/**
	 * Returns a queryBuilder object for travel expenses, based on the given filter
	 * @param array $filter
	 * @return Builder
	 */
	public function withFilter($filter) {
		$query = TravelExpense::with('WorkingHour')
			->select('kilometers.*')
			->join('werktijden', 'werktijd_id', '=', 'werktijden.id');
		
		// Apply filtering on date
		$query->where( 'datum', '>=', $filter['start']);
		$query->where( 'datum', '<=', $filter['end']);
		
		// Filter on project and relation as well
		foreach(array_only($filter, ['relatie_id', 'project_id']) as $field => $value) {
			if($value) {
				$query->where($field, $value);
			}
		}
				
		return $query;
	}
	
	/**
	 * Returns statistics about the totals per type of transportation
	 * 
	 * @param Builder $query
	 * @return Builder
	 */
	public function getStats($query) {
		return $query
			->select('wijze', DB::raw('SUM(afstand) as total'))
			->groupBy('wijze');
	}
	
}