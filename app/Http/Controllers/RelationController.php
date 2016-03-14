<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Relation;

use AppConfig;

class RelationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $relation = Relation::paginate(15);

        return view('relation.index', compact('relation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	
    
        return view('relation.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['bedrijfsnaam' => 'required', ]);

        Relation::create($request->all());

        Flash::message( 'Relation added!');

        return redirect('relation');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $relation = Relation::findOrFail($id);

        if($request->wantsJson()) {
        	return response()->json($relation);
        } else {
        	return view('relation.show', compact('relation'));
        }
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
        $relation = Relation::findOrFail($id);
    	$lists = [];
    	

        return view('relation.edit', compact('lists', 'relation'));
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
        $this->validate($request, ['bedrijfsnaam' => 'required', ]);

        $relation = Relation::findOrFail($id);
        $relation->update($request->all());

        Flash::message( 'Relation updated!');

        return redirect('relation');
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
        Relation::destroy($id);

        Flash::message( 'Relation deleted!');

        return redirect('relation');
    }

    /**
     * Returns the relation marked as self
     */
    public function getSelf(Request $request) {
    	return $this->show(AppConfig::get('relatieSelf'), $request);
    }
}
