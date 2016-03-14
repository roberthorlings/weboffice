<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Weboffice\TravelExpense;
use Illuminate\Http\Request;
use Flash;

class TravelExpenseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $travelexpense = TravelExpense::paginate(15);

        return view('travelexpense.index', compact('travelexpense'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["werktijd_id"] = \Weboffice\WorkingHour::all()->lists("description", "id");
    
        return view('travelexpense.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        TravelExpense::create($request->all());

        Flash::message( 'TravelExpense added!');

        return redirect('travelexpense');
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
        $travelexpense = TravelExpense::findOrFail($id);

        return view('travelexpense.show', compact('travelexpense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $travelexpense = TravelExpense::findOrFail($id);
    	$lists = [];
    	$lists["werktijd_id"] = \Weboffice\WorkingHour::all()->lists("description", "id");
    	
        return view('travelexpense.edit', compact('lists', 'travelexpense'));
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
        
        $travelexpense = TravelExpense::findOrFail($id);
        $travelexpense->update($request->all());

        Flash::message( 'TravelExpense updated!');

        return redirect('travelexpense');
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

}
