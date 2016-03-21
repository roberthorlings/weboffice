<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Weboffice\Models\Finance\Balance;

class BalanceController extends Controller
{

    /**
     * Shows the balance
     *
     * @return Response
     */
    public function index(Request $request)
    {
    	$filter = $this->getFilterFromRequest($request);
    	$balance = new Balance( $filter['date'] );
        return view('balance.index', compact('balance', 'filter'));
    }
    

    /**
     *
     * @param Request $request
     */
    protected function getFilterFromRequest(Request $request) {
    	$date = new Carbon($request->input('date', Carbon::now()));
    
    	// Build filter to use
    	$filter = [
    			'date' => $date,
    	];
    
    	return $filter;
    }    

}
