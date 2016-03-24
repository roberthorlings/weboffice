<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Carbon\Carbon;
use Session;
use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Invoice;
use Weboffice\Repositories\RelationRepository;
use Weboffice\Repositories\PostRepository;

class InvoiceController extends Controller
{
	const NUM_INVOICE_LINES = 8;
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(RelationRepository $repository, Request $request )
    {
    	$filter = $this->getFilterFromRequest($request);

    	$query = Invoice::orderBy('factuurnummer', 'desc')
    	->orderBy('versie', 'desc');
    	
    	// Apply filtering on date
    	$query->where( 'datum', '>=', $filter['start']);
    	$query->where( 'datum', '<=', $filter['end']);
    	
    	// Filter on project and relation as well
    	foreach(array_only($filter, ['relatie_id', 'project_id']) as $field => $value) {
    		if($value) {
    			$query->where($field, $value);
    		}
    	}
    	
        $invoices = $query->paginate(15);
        
        $relations = $repository->getRelationsWithProjects();

        return view('invoice.index', compact('invoices', 'filter', 'relations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(RelationRepository $relationRepository, PostRepository $postRepository)
    {
        $data = $this->getDataForForm(null, $relationRepository, $postRepository);
    	
        return view('invoice.create', $data);
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
     * Marks the given invoice as final
     * @param unknown $id
     */
    public function markAsFinal($id) 
    {
    	$invoice = Invoice::findOrFail($id);
    	 
    	// Make sure only one is marked as final
    	Invoice::where('factuurnummer', $invoice->factuurnummer)->update(['definitief' => false]);
    	
    	// Mark the current invoice as final
    	$invoice->definitief = true;
    	$invoice->save();
    	
    	Flash::message( 'Invoice marked as final' );
    	return redirect('invoice');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id, RelationRepository $relationRepository, PostRepository $postRepository)
    {
        $invoice = Invoice::findOrFail($id);
        
        $data = $this->getDataForForm($invoice, $relationRepository, $postRepository);
        return view('invoice.edit', $data);
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

        // Store the lines as well.
        $linesToSave = [];
        foreach( $request->get('Lines') as $lineInfo ) {
        	$invoice->updateLine($lineInfo['id'], $lineInfo['omschrijving'], $lineInfo['extra'], $lineInfo['aantal'], $lineInfo['prijs'], $lineInfo['post_id']);
        }
        
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
     * Returns data for the form for editing the specified resource.
     *
     *
     * @return Response
     */
    protected function getDataForForm($invoice, RelationRepository $relationRepository, PostRepository $postRepository)
    {
    	// Make sure to specify a set of lines with data
    	$numLines = self::NUM_INVOICE_LINES;
    	if( $invoice ) {
    		$numLines = max($numLines, count($invoice->InvoiceLines));
    	}
    
    	// Specify enough empty lines
    	$preEnteredLines = array_fill(0, $numLines, [ 'id' => null, 'omschrijving' => '', 'extra' => '', 'aantal' => null, 'prijs' => null, 'post_id' => null ]);
    	$sum = 0;
    
    	// Overwrite the first lines with existing data
    	if( $invoice ) {
    		foreach( $invoice->InvoiceLines as $idx => $line ) {
    			$preEnteredLines[$idx] = $line->toArray();
    			$sum += $line->bedrag;
    		}
    	}
    
    	// Specify the current relation-project combi
    	$relation_project = null;
    	if( $invoice ) {
    		if($invoice->project_id) {
    			$relation_project = 'project.' . $invoice->relatie_id . '.' . $invoice->project_id;
    		} else {
    			$relation_project = 'klant.' . $invoice->relatie_id;
    		}
    	}
    	
    	// Add a list of posts to choose from
    	$posts = $postRepository->getListForPostSelect();
    	$relations = $relationRepository->getRelationsWithProjects();
    
    	return compact('invoice', 'numLines', 'preEnteredLines', 'sum',  'posts', 'relations', 'relation_project');
    }
    
}
