<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Weboffice\Models\Finance\VATStatement;
use Laracasts\Flash\Flash;
use Weboffice\Support\Timespan;

class VATController extends Controller {
	
	/**
	 * Shows the balance
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$filter = $this->getFilterFromRequest ( $request );
		$vatstatement = new VATStatement ( $filter ['start'], $filter ['end'] );
		return view ( 'vat.index', compact ( 'vatstatement', 'filter' ) );
	}
	
	/**
	 * Books the proposed statements
	 * 
	 * @param Request $request        	
	 */
	public function book(Request $request) {
		// The filter is used to determine the period
		$filter = $this->getFilterFromRequest ( $request );
		$vatstatement = new VATStatement ( $filter ['start'], $filter ['end'] );
		
		// Store the bookings
		$vatstatement->saveStatements ();
		
		// Redirect the user
		Flash::message ( 'VAT has been booked for ' . Timespan::create ( $filter ['start'], $filter ['end'] ) );
		return redirect ( 'statement' );
	}
	
	/**
	 *
	 * @param Request $request        	
	 */
	protected function getFilterFromRequest(Request $request) {
		// Default start value is the start of last quarter
		$start = new Carbon ( $request->input ( 'start', Carbon::now ()->subMonth ()->firstOfQuarter () ) );
		$end = new Carbon ( $request->input ( 'end', $start->copy ()->lastOfQuarter () ) );
		
		// Build filter to use
		$filter = [ 
				'start' => $start,
				'end' => $end 
		];
		
		return $filter;
	}
}
