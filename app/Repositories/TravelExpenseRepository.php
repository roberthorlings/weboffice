<?php
namespace Weboffice\Repositories;

use Weboffice\Models\TravelExpense;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DB;
use AppConfig;

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
	
	/**
	 * Returns the most used addresses used 
	 * @param int $count 	Number of addresses to return
	 * @param Carbon $since Date to start searching for addresses. Defaults to 6 months ago
	 */
	public function getMostUsedAddresses($count = 3, $since = null) {
		if(!$since) {
			$since = Carbon::now()->subMonths(6);
		}
		
		// Query the most used addresses
		$query = TravelExpense::select('van_naar', 'bezoekadres', 'wijze', DB::raw('count(*) as total'))
					->join( 'werktijden', 'werktijden.id', '=', 'kilometers.werktijd_id')
					->where('datum', '>=', $since)
					->where('relatie_id', '<>', AppConfig::get('relatieSelf'))
					->groupBy('van_naar', 'bezoekadres', 'wijze')
					->orderBy('total', 'desc')
					->limit($count);

		return $query->get()->map(function($data) {
			// A visiting address may contain multiple addresses. Only use the first
			$addresses = explode(";", $data->bezoekadres);
			$parts = explode( ",", $addresses[0]);
			$data->address = [
				'name' => trim($parts[0]),
				'address' => count($parts) > 1 ? trim($parts[1]) : "",
				'city' => count($parts) > 2 ? trim($parts[2]) : ""
			];
			
			return $data;
		});
	}
	
}