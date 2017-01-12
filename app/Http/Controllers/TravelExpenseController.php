<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Weboffice\Models\TravelExpense;
use Weboffice\Models\Statement;
use Weboffice\Models\StatementLine;
use Illuminate\Http\Request;
use Flash;
use Carbon\Carbon;
use Session;
use AppConfig;
use Weboffice\Repositories\RelationRepository;
use Weboffice\Repositories\TravelExpenseRepository;
use Weboffice\Support\Timespan;

class TravelExpenseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(TravelExpenseRepository $repository, RelationRepository $relationRepository, Request $request)
    {
    	$filter = $this->getFilterFromRequest($request);
        
    	// Determine list of travel expenses and statistics about it
        $travelexpenses = $repository->withFilter($filter)->orderBy('datum', 'desc')->paginate(15);
        $stats 			= $repository->getStats($repository->withFilter($filter))->orderBy('wijze')->get();
        $total 			= array_sum(array_map(function($stat) { return $stat->total; }, $stats->all()));
        
        // Retrieve relation to show in filter
        $relations = $relationRepository->getRelationsWithProjects();

        return view('travelexpense.index', compact('travelexpenses', 'relations', 'stats', 'filter', 'total'));
    }

    /**
     * Display the specified list.
     *
     * @return Response
     */
    public function pdf(TravelExpenseRepository $repository, RelationRepository $relationRepository, Request $request)
    {
    	$filter = $this->getFilterFromRequest($request);
    	
    	// Determine list of travel expenses and statistics about it
    	$travelexpenses = $repository->withFilter($filter)->orderBy('datum', 'desc')->get();
    	$stats 			= $repository->getStats($repository->withFilter($filter))->orderBy('wijze')->get();
    	$total 			= array_sum(array_map(function($stat) { return $stat->total; }, $stats->all()));
    	$licenseplate		= AppConfig::get('kenteken');
    	$filename 		= (new Carbon())->format( 'ymd' ) . ' kilometerregistratie ' . Timespan::create($filter['start'], $filter['end']) . '.pdf';
    	 
    	return response()
    		->view('travelexpense.pdf', compact('travelexpenses', 'licenseplate', 'stats', 'filter', 'total', 'filename'))
    		->header('Content-Type', 'application/pdf')
    		->header('Content-disposition', 'attachment; filename="' . $filename . '"');
    }
        
    /**
     * Creates a statement for the given invoice
     */
    public function bookStatement(TravelExpenseRepository $repository, Request $request)
    {
    	$filter = $this->getFilterFromRequest($request);
    	 
    	// Compute total number of kilometers traveled
    	$stats 			= $repository->getStats($repository->withFilter($filter))->orderBy('wijze')->get();
    	$total 			= array_sum(array_map(function($stat) { return $stat->total; }, $stats->all()));
    	 
    	if($total == 0) {
    		Flash::message( 'No travel expenses made in the given period!' );
    		return redirect()->route( 'travelexpense.index', ['start' => $filter['start']->format('Y-m-d'), 'end' => $filter['end']->format('Y-m-d')]);
    	}
    	
    	// Determine configuration options
    	$creditorPostId = AppConfig::get('postPriveInvesteringen');
    	$costsPostId = AppConfig::get('postKilometerKosten');
    	$centsPerKilometer = AppConfig::get('kilometerVergoeding');
    	$amount = round($total * $centsPerKilometer) / 100;
    	 
    	// Create statement
    	$description = "Kilometervergoeding " . Timespan::create($filter['start'], $filter['end']);

    	// Create the statement itself
    	$statement = Statement::create([
   			'datum' => new Carbon(),
   			'omschrijving' => $description,
    		'opmerkingen' => $total . ' km tegen ' . $centsPerKilometer . ' cent per kilometer'
    	]);
    	
    	$statement->StatementLines()->save(new StatementLine(['bedrag' => $amount, 'credit' => 0, 'post_id' => $costsPostId ]));
    	$statement->StatementLines()->save(new StatementLine(['bedrag' => $amount, 'credit' => 1, 'post_id' => $creditorPostId ]));
    	 
    	Flash::success( 'A statement has been created for the given travel expenses' );
    	return redirect()->route('travelexpense.index', ['start' => $filter['start']->format('Y-m-d'), 'end' => $filter['end']->format('Y-m-d')]);
    }    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        TravelExpense::destroy($id);

        Flash::message( 'TravelExpense deleted!');

        return redirect('travelexpense');
    }
    

    /**
     *
     * @param Request $request
     */
    protected function getFilterFromRequest(Request $request) {
    	$relatieId = $request->input('relatie_id');
    	$projectId = $request->input('project_id');
    	$start = new Carbon($request->input('start', Session::get('start')));
    	$end  = new Carbon($request->input('end', Session::get('end')));
    	$type = $request->input('type');
    	
    	// Build filter to use
    	$filter = [
    		'start' => $start,
    		'end' => $end,
    		'type' => $type	
    	];
    	 
    	if($relatieId) {
    		$filter['relatie_id'] = $relatieId;
    
    		// To handle project, a relatie_id is required as well
    		if($projectId) {
    			$filter['project_id'] = $projectId;
    			$filter['relation_project'] = 'project.' . $relatieId . '.' . $projectId;
    		} else {
    			$filter['relation_project'] = 'klant.' . $relatieId;
    		}
    	}
    	 
    	 
    	return $filter;
    }    

}
