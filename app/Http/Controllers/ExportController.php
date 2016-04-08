<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Weboffice\Models\Finance\VATStatement;
use Laracasts\Flash\Flash;
use Weboffice\Support\Timespan;
use Session;
use Weboffice\Models\Finance\ProfitAndLossStatement;
use Weboffice\Models\Finance\Balance;
use Weboffice\Models\Finance\Ledgers;
use Weboffice\Models\Statement;
use Weboffice\Models\Post;
use Weboffice\Models\Saldo;


class ExportController extends Controller
{

    /**
     * Shows the balance
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $availableTypes = $this->getAvailableTypes();
        
        // Determine default period
        $start = Session::get('start');
        $end = Session::get('end');
        
        // Determine which years to be shown
        $startYear = Session::get('first')->year;
        $endYear = Carbon::now()->year;
        
    	return view('export.index', compact('availableTypes', 'start', 'end', 'startYear', 'endYear'));
    }
    
    /**
     * Export data to PDF
     * @param Request $request
     */
    public function export(Request $request) {
    	$start = new Carbon($request->input('start', Carbon::now()->subMonth()->firstOfQuarter()));
    	$end = new Carbon($request->input('end', $start->copy()->lastOfQuarter()));
    	
    	// Add optional data
    	$options = $request->input('option');
    	$data = [];
    	
    	foreach( $options as $type => $value ) {
    		$data[$type] = $this->getData($type, $start, $end);
    	}

    	$filename = 'export-weboffice.pdf';
    	return response()->view('export.pdf', compact('data', 'filename', 'start', 'end'))->header('Content-Type', 'application/pdf');
    }

    /**
     * Create year overview to PDF
     * @param Request $request
     */
    public function year($year, Request $request) {
    	$start = new Carbon($request->input('start', Carbon::now()->subMonth()->firstOfQuarter()));
    	$end = new Carbon($request->input('end', $start->copy()->lastOfQuarter()));
    
    	// The filter is used to determine the period
    	$filter = $this->getFilterFromRequest($request);
    	$vatstatement = new VATStatement( $filter['start'], $filter['end'] );
    	 
    	// Store the bookings
    	$vatstatement->saveStatements();
    
    	// Redirect the user
    	Flash::message( 'VAT has been booked for ' . Timespan::create($filter['start'], $filter['end']));
    	return redirect('statement');
    }
    
    protected function getAvailableTypes() {
    	return [
	    	'statements' 	=> 'Statements',
	    	'ledgers'		=> 'Ledgers',
	    	'balance'		=> 'Balance',
	    	'p-and-l'		=> 'Profit and Loss statement',
	    	'saldos'		=> 'Amounts due'
		];    	
    }
    
    protected function getData($type, Carbon $start, Carbon $end) {
    	switch($type) {
    		case 'statements': return $this->getStatements($start, $end);
    		case 'ledgers': return $this->getLedgers($start, $end);
    		case 'balance': return $this->getBalance($start, $end);
    		case 'p-and-l': return $this->getProfitAndLoss($start, $end);
    		case 'saldos': return $this->getSaldos($start, $end);
    	}
    	 
    }
    
    protected function getStatements(Carbon $start, Carbon $end) {
    	$query = Statement::with([
    		'StatementLines' => function($q) {
    			$q->orderBy('credit');
    		},
    		'StatementLines.Post'
    	])->orderBy('datum', 'asc');
    			 
		// Apply filtering on date
		$query->where( 'datum', '>=', $start);
		$query->where( 'datum', '<=', $end);
    			 
		return $query->get();
    }
    
    protected function getLedgers(Carbon $start, Carbon $end) {
    	$posts = Post::orderBy('nummer')->get();
    	return new Ledgers($start, $end, $posts);
    }
    
    protected function getBalance(Carbon $start, Carbon $end) {
    	return [
    		'start' => new Balance($start->copy()->subDay()),
    		'end' 	=> new Balance($end)	
    	];
    }
    
    protected function getProfitAndLoss(Carbon $start, Carbon $end) {
    	return new ProfitAndLossStatement($start, $end);
    }
    
    protected function getSaldos(Carbon $start, Carbon $end) {
    	$query = Saldo::openOn($end);
    	$query->with(['StatementLines' => function($query) use($end) {
    		$query->join('boekingen', 'boekingen.id', '=', 'boeking_delen.boeking_id');
    		return $query->where('datum', '<=', $end);
    	}]);
    	return $query->get();
    }
    
}
