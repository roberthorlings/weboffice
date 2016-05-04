<?php

namespace Weboffice\Http\Controllers;

use Flash;
use Session;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Weboffice\Http\Controllers\Controller;
use Weboffice\Models\WorkingHour;
use Weboffice\Models\Relation;
use Weboffice\Repositories\RelationRepository;
use Weboffice\Models\TravelExpense;
use Weboffice\Repositories\TravelExpenseRepository;

class WorkingHoursController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(RelationRepository $repository, Request $request)
    {
        $filter = $this->getFilterFromRequest($request);
        
    	$query = WorkingHour::with('Project', 'Relation')->orderBy('datum', 'desc')->orderBy('begintijd', 'desc');
        
        // Apply filtering on date
        $query->where( 'datum', '>=', $filter['start']);
        $query->where( 'datum', '<=', $filter['end']);
        
        // Filter on project and relation as well
        foreach(array_only($filter, ['relatie_id', 'project_id']) as $field => $value) {
        	if($value) {
        		$query->where($field, $value);
        	}
        }
        
        $workinghours = $query->paginate(30);
        $relations = $repository->getRelationsWithProjects();
        $relationsForEntry = $repository->getRelationsForWorkingHourEntry();
        
        return view('workinghours.index', compact('workinghours', 'relations', 'relationsForEntry', 'filter'));
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
		
		// Build filter to use
    	$filter = [
    		'start' => $start,
    		'end' => $end
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

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(RelationRepository $repository, TravelExpenseRepository $travelExpenseRepository, Request $request)
    {
    	$relations = $repository->getRelationsForWorkingHourEntry();
    	
    	// Default values can be supplied in the URL, when the user
    	// is sent here from the index page
    	$fields = ['relation_project', 'relatie_id', 'project_id', 'begintijd', 'eindtijd', 'pauze', 'opmerkingen'];
    	$defaultValues = [];
    	foreach($fields as $field) {
    		$defaultValues[$field] = $request->get($field);
    	}
    	
    	// Show a list of most used travel-registrations
    	$addresses = $travelExpenseRepository->getMostUsedAddresses();
    	
    	return view('workinghours.create', compact('relations', 'defaultValues', 'addresses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['datum' => 'required', 'begintijd' => 'required', 'eindtijd' => 'required', ]);

        $workinghour = WorkingHour::create($request->all());

        // Check whether the travel expense should be handled
        // There should only be a travel expense input if distance > 0
        $travelExpenseInput = $request->get('TravelExpense');
        if($workinghour->kilometers > 0) {
       		$travelExpense = new TravelExpense();
       		$travelExpense->afstand = $workinghour->kilometers;
       		$travelExpense->fill($travelExpenseInput);
       		$workinghour->travelExpense()->save($travelExpense);
        }        

        Flash::message( 'WorkingHour added!');

        return redirect('workinghours');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workinghour = WorkingHour::findOrFail($id);

        return view('workinghours.show', compact('workinghour'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id, RelationRepository $repository, TravelExpenseRepository $travelExpenseRepository)
    {
        $workinghour = WorkingHour::with('travelExpense')->findOrFail($id);
        $travelExpense = $workinghour->travelExpense ? $workinghour->travelExpense : new TravelExpense();
        
        // Returns all relations, as otherwise the old registrations may not be valid anymore
        $relations = $repository->getRelationsWithProjects(function($query) { return $query->where('type', '<>', Relation::TYPE_SUPPLIER); });
        
        // Show a list of most used travel-registrations
        $addresses = $travelExpenseRepository->getMostUsedAddresses();
         
        
        return view('workinghours.edit', compact('workinghour', 'travelExpense', 'relations', 'addresses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['datum' => 'required', 'begintijd' => 'required', 'eindtijd' => 'required', ]);

        $workinghour = WorkingHour::findOrFail($id);
        $workinghour->update($request->all());

        // Check whether the travel expense should be handled
        // There should only be a travel expense input if distance > 0
        $travelExpenseInput = $request->get('TravelExpense');
        if($workinghour->kilometers > 0) {
        	$travelExpenseInput['afstand'] = $workinghour->kilometers;
        	if(!$workinghour->travelExpense) {
        		$travelExpense = new TravelExpense();
        		$travelExpense->fill($travelExpenseInput);
        		$workinghour->travelExpense()->save($travelExpense);
        	} else {
        		$workinghour->travelExpense->update($travelExpenseInput);
        	}
        } else if( $workinghour->travelExpense ) {
        	$workinghour->travelExpense->delete();
        }
        
        Flash::message( 'WorkingHour updated!');

        return redirect('workinghours');
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
        WorkingHour::destroy($id);

        Flash::message( 'WorkingHour deleted!');

        return redirect('workinghours');
    }

}
