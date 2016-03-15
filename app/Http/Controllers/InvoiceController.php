<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Invoice;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoice = Invoice::paginate(15);

        return view('invoice.index', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Relation::lists("bedrijfsnaam", "id");
		$lists["project_id"] = \Weboffice\Project::all()->lists("description", "id");
    
        return view('invoice.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Invoice::create($request->all());

        Flash::message( 'Invoice added!');

        return redirect('invoice');
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
        $invoice = Invoice::findOrFail($id);

        return view('invoice.show', compact('invoice'));
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
        $invoice = Invoice::findOrFail($id);
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Relation::lists("bedrijfsnaam", "id");
		$lists["project_id"] = \Weboffice\Project::all()->lists("description", "id");
    	
        return view('invoice.edit', compact('lists', 'invoice'));
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
        
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->all());

        Flash::message( 'Invoice updated!');

        return redirect('invoice');
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
        Invoice::destroy($id);

        Flash::message( 'Invoice deleted!');

        return redirect('invoice');
    }

}
