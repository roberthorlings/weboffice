<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use AppConfig;
use Illuminate\Http\Request;
use Weboffice\Models\Quote;
use Flash;
use Carbon\Carbon;
use Session;
use Weboffice\Repositories\RelationRepository;
use Weboffice\Models\Project;

class QuoteController extends Controller
{
	const NUM_QUOTE_LINES = 6;
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, RelationRepository $repository)
    {
    	$filter = $this->getFilterFromRequest($request);
    	
    	$query = Quote::orderBy('offertenummer', 'desc')
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
    	 
    	$quotes = $query->paginate(15);
    	
    	$relations = $repository->getRelationsWithProjects();
    	
    	return view('quote.index', compact('quotes', 'filter', 'relations'));    	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(RelationRepository $relationRepository)
    {
    	$data = $this->getDataForForm(null, $relationRepository);
    	
    	// Set default values
    	$data['date'] = Carbon::now();
    	$data['expiry_date'] = Carbon::now()->addMonth();
    	$data['number'] = Quote::nextNumber();
    	
        return view('quote.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
    	// Create the new invoice
    	$data = $request->all();
    	$data['versie'] = Quote::nextVersionNumber($data['offertenummer']);
    	 
    	$quote = Quote::create($data);
    	
    	// Store optional projects belonging to the invoice
    	$lines = $request->get('Lines');
    	
    	// Store the lines as well.
    	foreach( $lines as $lineInfo ) {
    		$quote->addLine($lineInfo['titel'], $lineInfo['inhoud']);
    	}
    	
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
        $quote = Quote::with('QuoteLines')->findOrFail($id);

        return view('quote.show', compact('quote'));
    }
    
    /**
     * Display the specified resource as pdf
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function pdf($id)
    {
    	$quote = Quote::findOrFail($id);
    	$filename = 'quote ' . $quote->offertenummer . '.pdf';
    	$quoteNumberPrefix = AppConfig::get('offerteNummerPrefix');
    	return response()->view('quote.pdf', compact('quote', 'filename', 'quoteNumberPrefix'))->header('Content-Type', 'application/pdf');
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id, RelationRepository $relationRepository)
    {
        $quote = Quote::with('QuoteLines')->findOrFail($id);
        
        $data = $this->getDataForForm($quote, $relationRepository);
        
        return view('quote.edit', $data);
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
        $data = $request->all();
        $lines = $request->get('Lines');
        
        if( $request->get( 'save-method', 'new-version' ) == 'new-version' ) {
        	// Create new invoice and update version number
        	$data['versie'] = Quote::nextVersionNumber($data['offertenummer']);
        	$quote = Quote::create($data);
        
        	// Store the lines as well. Please note that new lines will be created, so the id is irrelevant
        	foreach( $lines as $lineInfo ) {
        		$quote->updateLine(null, $lineInfo['titel'], $lineInfo['inhoud']);
        	}
        } else {
        	// Update current version
        	$quote = Quote::findOrFail($id);
        	$quote->update($data);

        	// Store the lines as well.
        	foreach( $lines as $lineInfo ) {
        		$quote->updateLine($lineInfo['id'], $lineInfo['titel'], $lineInfo['inhoud']);
        	}
        }
        
        Flash::message( 'Quote updated!');
        
        return redirect()->route('quote.show', [$quote->id]);        
        
        
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

    /**
     * Marks the given quote as final
     * @param unknown $id
     */
    public function markAsFinal($id)
    {
    	$quote = Quote::findOrFail($id);
    
    	// Make sure only one is marked as final
    	Quote::where('offertenummer', $quote->offertenummer)->update(['definitief' => false]);
    
    	// Mark the current invoice as final
    	$quote->definitief = true;
    	$quote->save();
    
    	Flash::message( 'Quote marked as final' );
    	return redirect('quote');
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
     * @return Response
     */
    protected function getDataForForm($quote, RelationRepository $relationRepository)
    {
    	// Make sure to specify a set of lines with data
    	$numLines = self::NUM_QUOTE_LINES;
    	if( $quote ) {
    		$numLines = max($numLines, count($quote->QuoteLines));
    	}
    
    	// Specify enough empty lines
    	$preEnteredLines = array_fill(0, $numLines, [ 'id' => null, 'titel' => '', 'inhoud' => '' ]); 
    
    	// Overwrite the first lines with existing data
    	if( $quote ) {
    		foreach( $quote->QuoteLines as $idx => $line ) {
    			$preEnteredLines[$idx] = $line->toArray();
    		}
    	} else {
    		// Add default values
    		$preEnteredLines[0]['titel' ] = 'Opdrachtomschrijving';
    		$preEnteredLines[1]['titel' ] = 'Doelen / te behalen resultaat';
    		$preEnteredLines[2]['titel' ] = 'Werkwijze';
    		$preEnteredLines[3]['titel' ] = 'Duur van de opdracht';
    		$preEnteredLines[4]['titel' ] = 'Kosten';
    	}
    
    	// Specify the current relation-project combi
    	$relation_project = null;
    	if( $quote ) {
    		if($quote->project_id) {
    			$relation_project = 'project.' . $quote->relatie_id . '.' . $quote->project_id;
    		} else {
    			$relation_project = 'klant.' . $quote->relatie_id;
    		}
    	}
    	
    	$relations = $relationRepository->getRelationsWithProjects(null, function($builder) {
    		return $builder->where( 'status', '<', Project::STATUS_AFGEROND );
    	})->filter(function($relation) {
    		return count($relation->Projects) > 0;
    	});    	
    		 
   		return compact('quote', 'numLines', 'preEnteredLines', 'relations', 'relation_project');
    }    
}
