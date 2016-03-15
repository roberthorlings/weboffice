<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Weboffice\Models\Balance;

class BalanceController extends Controller
{

    /**
     * Shows the balance
     *
     * @return Response
     */
    public function index()
    {
    	$balance = new Balance( Carbon::now() );
        return view('balance.index', compact('balance'));
    }

}
