<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Weboffice\Models\TravelExpense;
use Illuminate\Http\Request;
use Flash;
use Carbon\Carbon;
use Session;
use Weboffice\Repositories\RelationRepository;
use Weboffice\Repositories\TravelExpenseRepository;

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
