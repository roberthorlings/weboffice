<?php

namespace Weboffice\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Session;
use View;

/**
 * Class SessionFilter
 *
 * @package Weboffice\Http\Middleware
 */
class DateRange {
	
	/**
	 * Makes sure that the start, end and first dates are available in the response
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @param Closure $theNext        	
	 *
	 * @return mixed
	 * @internal param Closure $next
	 */
	public function handle(Request $request, Closure $theNext) {
		// By default, set the range to the previous three months
		if (! Session::has ( 'start' ) && ! Session::has ( 'end' )) {
			$start = Carbon::now()->subMonth(3)->startOfMonth();
			$end = Carbon::now()->endOfMonth();
			Session::put ( 'start', $start );
			Session::put ( 'end', $end );
		}
		if (! Session::has ( 'first' )) {
			$first = new Carbon( 'March 17, 2010' );
			Session::put ( 'first', $first );
		}
		
		// Add everything to the views
		View::share ( 'daterangeStart', Session::get('start') );
		View::share ( 'daterangeEnd', Session::get('end') );
		View::share ( 'daterangeFirst', Session::get('first') );
		
		return $theNext ( $request );
	}
}