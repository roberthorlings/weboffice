<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Session;
use Weboffice\Models\Statement;
use Weboffice\Repositories\PostRepository;


class StatementController extends Controller
{
	const NUM_STATEMENT_LINES = 6;
	
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
    public function create(PostRepository $repository)
    {
    	$data = $this->getDataForForm(null, $repository);
        return view('statement.create', $data);
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
    public function edit($id, PostRepository $repository)
    {
        $statement = Statement::findOrFail($id);
    	$data = $this->getDataForForm($statement, $repository);
        return view('statement.edit', $data);
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

        // Store the lines as well.
        $linesToSave = [];
        foreach( $request->get('Lines') as $lineInfo ) {
        	$statement->updateLine($lineInfo['id'], $lineInfo['credit'], $lineInfo['amount'], $lineInfo['post_id']);
        }        
        
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
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    protected function getDataForForm($statement, PostRepository $repository)
    {
    	// Make sure to specify a set of lines with data
    	$numLines = self::NUM_STATEMENT_LINES;
    	if( $statement ) {
    		$numLines = max($numLines, count($statement->StatementLines));
    	}
    
    	// Specify enough empty lines
    	$preEnteredLines = array_fill(0, $numLines, [ 'id' => null, 'credit' => 0, 'amount' => null, 'post_id' => null ]);
    	$sum = 0;
    
    	// Overwrite the first lines with existing data
    	if( $statement ) {
    		foreach( $statement->StatementLines as $idx => $line ) {
   				$preEnteredLines[$idx] = [ 'id' => $line->id, 'credit' => $line->credit, 'amount' => number_format($line->bedrag, 2, '.', ''), 'post_id' => $line->post_id ];
   				$sum += $line->getSignedAmount();
    		}
    	}
    
    	// Add a list of posts to choose from
    	$posts = $repository->getListForPostSelect();
    
    	return compact('statement', 'numLines', 'preEnteredLines', 'sum',  'posts');
    }    

}
