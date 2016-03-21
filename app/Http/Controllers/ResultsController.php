<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Weboffice\Models\Finance\ProfitAndLossStatement;

class ResultsController extends Controller
{

    /**
     * Shows the balance
     *
     * @return Response
     */
    public function index(Request $request)
    {
    	$filter = $this->getFilterFromRequest($request);
    	 
    	$statement = new ProfitAndLossStatement( $filter['start'], $filter['end']);
        return view('results.index', compact('statement', 'filter'));
    }
    
    /**
     *
     * @param Request $request
     */
    protected function getFilterFromRequest(Request $request) {
    	$start = new Carbon($request->input('start', Session::get('start')));
    	$end  = new Carbon($request->input('end', Session::get('end')));
    
    	// Build filter to use
    	$filter = [
    			'start' => $start,
    			'end' => $end
    	];
    	 
    	return $filter;
    }    

}
