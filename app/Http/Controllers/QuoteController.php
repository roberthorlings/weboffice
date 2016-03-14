<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Weboffice\Quote;
use Flash;

class QuoteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $quote = Quote::paginate(15);

        return view('quote.index', compact('quote'));
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
		$lists["project_id"] = \Weboffice\Project::lists("naam", "id");
    
        return view('quote.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Quote::create($request->all());

        Flash::message( 'Quote added!');

        return redirect('quote');
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
        $quote = Quote::findOrFail($id);

        return view('quote.show', compact('quote'));
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
        $quote = Quote::findOrFail($id);
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Relation::lists("bedrijfsnaam", "id");
		$lists["project_id"] = \Weboffice\Project::lists("naam", "id");
    	
        return view('quote.edit', compact('lists', 'quote'));
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
        
        $quote = Quote::findOrFail($id);
        $quote->update($request->all());

        Flash::message( 'Quote updated!');

        return redirect('quote');
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
        Quote::destroy($id);

        Flash::message( 'Quote deleted!');

        return redirect('quote');
    }

}
