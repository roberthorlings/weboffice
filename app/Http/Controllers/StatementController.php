<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Session;
use Weboffice\Statement;


class StatementController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
    	$filter = $this->getFilterFromRequest($request);
    	
    	$query = Statement::with([
    			'StatementLines' => function($q) {
    				$q->orderBy('credit');
    			},
    			'StatementLines.Post'
    	])->orderBy('datum', 'desc');
    	
    	// Apply filtering on date
    	$query->where( 'datum', '>=', $filter['start']);
    	$query->where( 'datum', '<=', $filter['end']);
    	
    	// Filter on project and relation as well
    	if(array_key_exists('post_id', $filter)) {
    		$query->bookedOnPost($filter['post_id']);
    	}
    	
    	$statements = $query->paginate(15);    	
        return view('statement.index', compact('statements', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["transactie_id"] = \Weboffice\Transaction::lists("omschrijving", "id");
		$lists["activum_id"] = \Weboffice\Asset::lists("omschrijving", "id");
    
        return view('statement.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Statement::create($request->all());

        Flash::message( 'Statement added!');

        return redirect('statement');
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
        $statement = Statement::findOrFail($id);

        return view('statement.show', compact('statement'));
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
        $statement = Statement::findOrFail($id);
    	$lists = [];
    	$lists["transactie_id"] = \Weboffice\Transaction::lists("omschrijving", "id");
		$lists["activum_id"] = \Weboffice\Asset::lists("omschrijving", "id");

        return view('statement.edit', compact('lists', 'statement'));
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
        
        $statement = Statement::findOrFail($id);
        $statement->update($request->all());

        Flash::message( 'Statement updated!');

        return redirect('statement');
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
        Statement::destroy($id);

        Flash::message( 'Statement deleted!');

        return redirect('statement');
    }
    

    /**
     *
     * @param Request $request
     */
    protected function getFilterFromRequest(Request $request) {
    	$postId = $request->input('post_id');
    	$start = new Carbon($request->input('start', Session::get('start')));
    	$end  = new Carbon($request->input('end', Session::get('end')));
    
    	// Build filter to use
    	$filter = [
    			'start' => $start,
    			'end' => $end
    	];
    		
    	if($postId)
    		$filter['post_id'] = $postId;
    
    		return $filter;
    }    

}
