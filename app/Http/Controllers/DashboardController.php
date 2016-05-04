<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Weboffice\Models\Finance\Balance;
use Weboffice\Models\Saldo;
use Weboffice\Models\Finance\ProfitAndLossStatement;
use Weboffice\Models\WorkingHour;

class DashboardController extends Controller
{

    /**
     * Shows the dashboard
     *
     * @return Response
     */
    public function index()
    {
    	// Show the correct information on screen
    	$balance = new Balance(Carbon::now());
    	$saldos = Saldo::open()
    				->with(['StatementLines', 'StatementLines.Statement', 'Relation'])
    				->orderBy('id', 'desc')
    				->paginate(15);
    	$profitAndLossStatement = new ProfitAndLossStatement( Carbon::now()->startOfYear(), Carbon::now());
    	$workingHours = $this->getGroupedWorkingHours();
    	 
        return view('dashboard.index', compact('balance', 'saldos', 'profitAndLossStatement', 'workingHours'));
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
    
    /**
     * Returns working hours grouped by month
     */
    protected function getGroupedWorkingHours() {
    	$period = [ Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->endOfMonth()];
    	$workingHours = WorkingHour::with(['Relation'])
	    	->where('datum', '>=', $period[0] )
    		->where('datum', '<=', $period[1])
    		->orderBy('datum', 'desc')
    		->get();
    	
    	// Group the data by month and relation
    	$groupedData = [];
    	foreach($workingHours as $workingHour) {
    		$month = $workingHour->datum->formatLocalized('%B');
    		$relation = $workingHour->Relation;
    		$relationId = $relation ? $relation->id : 0;
    		
    		if(!array_key_exists($month, $groupedData))
    			$groupedData[$month] = [];
    		
    		if(!array_key_exists($relationId, $groupedData[$month])) {
    			$groupedData[$month][$relationId] = ['relation' => $relation, 'total' => 0];
    		}
    		
    		$groupedData[$month][$relationId]['total'] += $workingHour->durationInMinutes;
    	}
    	
    	return $groupedData;
    }
}
