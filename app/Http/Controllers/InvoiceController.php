<?php

namespace Weboffice\Http\Controllers;

use Carbon\Carbon;
use Flash;
use AppConfig;
use Illuminate\Http\Request;
use Session;
use Weboffice\Http\Controllers\Controller;
use Weboffice\Models\Invoice;
use Weboffice\Repositories\PostRepository;
use Weboffice\Repositories\RelationRepository;
use Weboffice\Models\Project;

class InvoiceController extends Controller
{
	const NUM_INVOICE_LINES = 8;
	const NUM_PROJECT_LINES = 4;
	
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
        $data['creditnote' ] = false;
        
        // Set default values
        $data['date'] = Carbon::now();
        $data['number'] = Invoice::nextNumber();
        
        return view('invoice.create-default', $data);
    }

    /**
     * Show the form for creating a credit note
     *
     * @return Response
     */
    public function createCreditNote(RelationRepository $relationRepository, PostRepository $postRepository)
    {
    	$data = $this->getDataForForm(null, $relationRepository, $postRepository);
    	$data['creditnote' ] = true;
    	
    	// Set default values
    	$data['date'] = Carbon::now();
    	$data['number'] = Invoice::nextNumber();
    
    	return view('invoice.create-default', $data);
    }
        
    /**
     * Show the form for creating a new project invoice
     *
     * @return Response
     */
    public function createProjectInvoice(RelationRepository $relationRepository, PostRepository $postRepository)
    {
    	$data = $this->getDataForForm(null, $relationRepository, $postRepository);
    	$data = array_merge( $data, $this->getDataForProjectForm(null, $relationRepository));
    	 
    	// Set default values
    	$data['date'] = Carbon::now();
    	$data['number'] = Invoice::nextNumber();
    	
    	return view('invoice.create-project', $data);
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
    	$data['versie'] = Invoice::nextVersionNumber($data['factuurnummer']);
    	
    	$invoice = Invoice::create($data);

        // Store optional projects belonging to the invoice
        $lines = $request->get('Lines');
        
        if( $invoice->uurtje_factuurtje ) {
        	foreach( $request->get('Projects', []) as $idx => $projectInfo ) {
        		// Add the project to the invoice
        		$invoiceProject = $invoice->addProject($projectInfo['project_id'], $projectInfo['start'], $projectInfo['end'], $projectInfo['hours_overview_type']);
        		
        		// Update invoice lines accordingly
        		if( $invoiceProject ) {
        			$lines[$idx]['omschrijving' ] = $invoiceProject->Project->naam;
        			$lines[$idx]['aantal'] = $invoiceProject->getTotalWorkingHours();
        			$lines[$idx]['prijs'] = $invoiceProject->getHourlyRate();
        			$lines[$idx]['post_id'] = $invoiceProject->Project->post_id;
        		}
        	}
        }
        
        // Store the lines as well.
        foreach( $lines as $lineInfo ) {
        	$invoice->addLine($lineInfo['omschrijving'], $lineInfo['extra'], $lineInfo['aantal'], $lineInfo['prijs'], $lineInfo['post_id']);
        }
        
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
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function pdf($id)
    {
    	$invoice = Invoice::findOrFail($id);
    	$filename = 'invoice ' . $invoice->factuurnummer . '.pdf';
    	$invoiceNumberPrefix = AppConfig::get('factuurNummerPrefix');
    	return response()->view('invoice.pdf', compact('invoice', 'filename', 'invoiceNumberPrefix'))->header('Content-Type', 'application/pdf');
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
        $invoice = Invoice::with(['InvoiceProjects', 'InvoiceLines'])->findOrFail($id);
        
        $data = $this->getDataForForm($invoice, $relationRepository, $postRepository);
        
        if( $invoice->uurtje_factuurtje ) {
        	$data = array_merge( $data, $this->getDataForProjectForm($invoice, $relationRepository));
        	return view('invoice.edit-project', $data);
        } else {
        	return view('invoice.edit-default', $data);
        }
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
        	$data['versie'] = Invoice::nextVersionNumber($data['factuurnummer']);
        	$invoice = Invoice::create($data);

        	// Store optional projects belonging to the invoice
        	if( $invoice->uurtje_factuurtje ) {
        		foreach( $request->get('Projects', []) as $idx => $projectInfo ) {
        			$invoiceProject = $invoice->updateProject(null, $projectInfo['project_id'], $projectInfo['start'], $projectInfo['end'], $projectInfo['hours_overview_type']);
        			 
        			// Update invoice lines accordingly
        			if( $invoiceProject ) {
        				$lines[$idx]['omschrijving' ] = $invoiceProject->Project->naam;
        				$lines[$idx]['aantal'] = $invoiceProject->getTotalWorkingHours();
        				$lines[$idx]['prijs'] = $invoiceProject->getHourlyRate();
        				$lines[$idx]['post_id'] = $invoiceProject->Project->post_id;
        			}
        		}
        	}
        	 
        	// Store the lines as well. Please note that new lines will be created, so the id is irrelevant
        	foreach( $lines as $lineInfo ) {
        		$invoice->updateLine(null, $lineInfo['omschrijving'], $lineInfo['extra'], $lineInfo['aantal'], $lineInfo['prijs'], $lineInfo['post_id'], $lineInfo['project_id']);
        	}
        } else {
        	// Update current version
        	$invoice = Invoice::findOrFail($id);
        	$invoice->update($data);
        	
        	// Store optional projects belonging to the invoice
        	if( $invoice->uurtje_factuurtje ) {
        		foreach( $request->get('Projects', []) as $idx => $projectInfo ) {
        			$invoiceProject = $invoice->updateProject($projectInfo['id'], $projectInfo['project_id'], $projectInfo['start'], $projectInfo['end'], $projectInfo['hours_overview_type']);
        			 
        			// Update invoice lines accordingly
        			if( $invoiceProject ) {
        				$lines[$idx]['omschrijving' ] = $invoiceProject->Project->naam;
        				$lines[$idx]['aantal'] = $invoiceProject->getTotalWorkingHours();
        				$lines[$idx]['prijs'] = $invoiceProject->getHourlyRate();
        				$lines[$idx]['post_id'] = $invoiceProject->Project->post_id;
        			}
        		}
        	}
        	
        	// Store the lines as well.
        	foreach( $lines as $lineInfo ) {
        		$invoice->updateLine($lineInfo['id'], $lineInfo['omschrijving'], $lineInfo['extra'], $lineInfo['aantal'], $lineInfo['prijs'], $lineInfo['post_id'], $lineInfo['project_id']);
        	}
        }
        
        Flash::message( 'Invoice updated!');

        return redirect()->route('invoice.show', [$invoice->id]);
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
     * Creates a statement for the given invoice
     */
    public function bookStatement($id)
    {
    	$invoice = Invoice::findOrFail($id);
    	$invoice->saveStatement();

    	Flash::success( 'A statement has been created for the given invoice' );
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
    	$preEnteredLines = array_fill(0, $numLines, [ 'id' => null, 'omschrijving' => '', 'extra' => '', 'aantal' => null, 'prijs' => null, 'post_id' => null, 'project_id' => null ]);
    	$sum = 0;
    
    	// Overwrite the first lines with existing data
    	if( $invoice ) {
    		foreach( $invoice->InvoiceLines as $idx => $line ) {
    			$preEnteredLines[$idx] = $line->toArray();
    			$preEnteredLines[$idx]['aantal'] = (float) $line->aantal;
    			$preEnteredLines[$idx]['prijs'] = (float) $line->prijs;
    			$preEnteredLines[$idx]['project_id'] = (float) $line->project_id;
    			$sum += $line->getSubtotal();
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
    	$relations = $relationRepository->getRelationsWithProjects(null, function($builder) {
    		return $builder->where( 'status', Project::STATUS_ACTIEF );
    	})->filter(function($relation) {
    		return count($relation->Projects) > 0;
    	});
    	
    	return compact('invoice', 'numLines', 'preEnteredLines', 'sum',  'posts', 'relations', 'relation_project');
    }
    
    /**
     * Returns the data needed for the form to create/edit a project invoice
     */
    protected function getDataForProjectForm($invoice, RelationRepository $relationRepository) {
    	// Add projects
    	$relations = $relationRepository->getRelationsWithProjects(null, function($builder) {
    		return $builder->where( 'status', Project::STATUS_ACTIEF );
    	})->filter(function($relation) {
    		return count($relation->Projects) > 0;
    	});
    		 
   		$projects = [];
   		foreach( $relations as $relation ) {
   			if(count($relation->Projects))
   				$projects[ $relation->bedrijfsnaam ] = $relation->Projects->pluck("naam", "id")->all();
  		}
  		
  		// Make sure to specify a set of lines with data
  		$numProjects = self::NUM_PROJECT_LINES;
  		if( $invoice ) {
  			$numProjects = max($numProjects, count($invoice->InvoiceProjects));
  		}
  		
  		// Specify enough empty lines
  		$lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
  		$lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
  		$preEnteredProjects = array_fill(0, $numProjects, [ 'id' => null, 'project_id' => null, 'start' => $lastMonthStart, 'end' => $lastMonthEnd, 'hours_overview_type' => 'defualt' ]);
  		
  		// Overwrite the first lines with existing data
  		if( $invoice ) {
  			foreach( $invoice->InvoiceProjects as $idx => $line ) {
  				$preEnteredProjects[$idx] = $line->toArray();
  				$preEnteredProjects[$idx]['start'] = $line->start;
  				$preEnteredProjects[$idx]['end'] = $line->end;
  			}
  		}  		
  		
  		return compact( 'projects', 'numProjects', 'preEnteredProjects', 'relations');
    }
    
}
