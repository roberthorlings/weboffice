<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Session;
use Weboffice\Models\Finance\ProfitAndLossStatement;

class ResultsController extends Controller
{

    /**
     * Shows the balance
     *
     * @return Response
     */
    public function index()
    {
    	$start = Session::get('first');
    	$end = Carbon::now();
    	$statement = new ProfitAndLossStatement( $start, $end );
    	
        return view('results.index', compact('statement', 'start', 'end'));
    }

}
