<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Weboffice\Models\Finance\Balance;

class BalanceController extends Controller
{

    /**
     * Shows the balance
     *
     * @return Response
     */
    public function index()
    {
    	$date = Carbon::now();
    	$balance = new Balance( $date );
        return view('balance.index', compact('balance', 'date'));
    }

}
