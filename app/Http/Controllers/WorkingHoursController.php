<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Requests;
use Weboffice\Http\Controllers\Controller;

use Weboffice\WorkingHour;
use Weboffice\Relation;
use Weboffice\Repositories\RelationRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Weboffice\TravelExpense;

class WorkingHoursController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = WorkingHour::orderBy('datum', 'desc')->orderBy('begintijd', 'desc');
        
        // Apply filtering on date
        $query->where( 'datum', '>=', Session::get('start'));
        $query->where( 'datum', '<=', Session::get('end'));
        
        $workinghours = $query->paginate(30);

        return view('workinghours.index', compact('workinghours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(RelationRepository $repository)
    {
    	$relations = $repository->getRelationsForWorkingHourEntry();
    	return view('workinghours.create', compact( 'relations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['datum' => 'required', 'begintijd' => 'required', 'eindtijd' => 'required', ]);

        WorkingHour::create($request->all());

        Session::flash('flash_message', 'WorkingHour added!');

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
    public function edit($id, RelationRepository $repository)
    {
        $workinghour = WorkingHour::with('travelExpense')->findOrFail($id);
        $travelExpense = $workinghour->travelExpense ? $workinghour->travelExpense : new TravelExpense();
        
        // Returns all relations, as otherwise the old registrations may not be valid anymore
        $relations = $repository->getRelationsWithProjects(function($query) { return $query->where('type', '<>', Relation::TYPE_SUPPLIER); });
        
        return view('workinghours.edit', compact('workinghour', 'travelExpense', 'relations'));
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

        Session::flash('flash_message', 'WorkingHour updated!');

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

        Session::flash('flash_message', 'WorkingHour deleted!');

        return redirect('workinghours');
    }

}
