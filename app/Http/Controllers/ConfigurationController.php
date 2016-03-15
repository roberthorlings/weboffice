<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Configuration;

class ConfigurationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $configuration = Configuration::paginate(15);

        return view('configuration.index', compact('configuration'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	
    
        return view('configuration.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Configuration::create($request->all());

        Flash::message( 'Configuration added!');

        return redirect('configuration');
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
        $configuration = Configuration::findOrFail($id);

        return view('configuration.show', compact('configuration'));
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
        $configuration = Configuration::findOrFail($id);
    	$lists = [];
    	

        return view('configuration.edit', compact('lists', 'configuration'));
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
        
        $configuration = Configuration::findOrFail($id);
        $configuration->update($request->all());

        Flash::message( 'Configuration updated!');

        return redirect('configuration');
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
        Configuration::destroy($id);

        Flash::message( 'Configuration deleted!');

        return redirect('configuration');
    }

}
