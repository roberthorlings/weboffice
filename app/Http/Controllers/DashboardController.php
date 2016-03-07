<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Requests;
use Weboffice\Http\Controllers\Controller;

use Weboffice\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class DashboardController extends Controller
{

    /**
     * Shows the dashboard
     *
     * @return Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    // Updates the date range
    public function dateRange(Request $request)
    {
    
    	$start = new Carbon($request->get('start'));
    	$end   = new Carbon($request->get('end'));
    
    	$diff = $start->diffInDays($end);
    
    	if ($diff > 50) {
    		Session::flash('warning', $diff . ' days of data may take a while to load.');
    	}
    
    	Session::put('start', $start);
    	Session::put('end', $end);
    }
    
}
