<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Session;
use Weboffice\Models\Asset;
use Weboffice\Models\Project;
use Weboffice\Models\Relation;
use Weboffice\Models\Saldo;
use Weboffice\Models\Statement;
use Weboffice\Repositories\PostRepository;
use AppConfig;

class StatementController extends Controller {
	const NUM_STATEMENT_LINES = 6;
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$filter = $this->getFilterFromRequest ( $request );
		
		$query = Statement::with ( [ 
				'StatementLines' => function ($q) {
					$q->orderBy ( 'credit' );
				},
				'StatementLines.Post' 
		] )->orderBy ( 'datum', 'desc' );
		
		// Apply filtering on date
		$query->where ( 'datum', '>=', $filter ['start'] );
		$query->where ( 'datum', '<=', $filter ['end'] );
		
		// Filter on project and relation as well
		if (array_key_exists ( 'post_id', $filter )) {
			$query->bookedOnPost ( $filter ['post_id'] );
		}
		
		$statements = $query->paginate ( 15 );
		return view ( 'statement.index', compact ( 'statements', 'filter' ) );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(PostRepository $repository) {
		$data = $this->getDataForForm ( null, $repository );
		return view ( 'statement.create', $data );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		$statement = Statement::create ( $request->all () );
		
		// Store the lines as well.
		$linesToSave = [ ];
		foreach ( $request->get ( 'Lines' ) as $lineInfo ) {
			$statement->addLine ( $lineInfo ['credit'], $lineInfo ['amount'], $lineInfo ['post_id'] );
		}
		
		Flash::message ( 'Statement added!' );
		
		return redirect ( 'statement' );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function show($id) {
		$statement = Statement::findOrFail ( $id );
		
		return view ( 'statement.show', compact ( 'statement' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function edit($id, PostRepository $repository) {
		$statement = Statement::findOrFail ( $id );
		$data = $this->getDataForForm ( $statement, $repository );
		return view ( 'statement.edit', $data );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function update($id, Request $request) {
		$statement = Statement::findOrFail ( $id );
		$statement->update ( $request->all () );
		
		// Store the lines as well.
		$linesToSave = [ ];
		foreach ( $request->get ( 'Lines' ) as $lineInfo ) {
			$statement->updateLine ( $lineInfo ['id'], $lineInfo ['credit'], $lineInfo ['amount'], $lineInfo ['post_id'] );
		}
		
		return redirect ( 'statement' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function destroy($id) {
		Statement::destroy ( $id );
		
		Flash::message ( 'Statement deleted!' );
		
		return redirect ( 'statement' );
	}
	
	/**
	 * Show a form to add a new statement for an incoming invoice
	 * 
	 * @param PostReposity $repository        	
	 */
	public function incomingInvoice(PostRepository $postRepository) {
		$data = $this->getDataForForm ( null, $postRepository );
		$data ['relations'] = Relation::orderBy ( 'bedrijfsnaam' )->lists ( 'bedrijfsnaam', 'id' );
		$data ['projects'] = Project::lists ( 'naam', 'id' );
		$data ['date'] = Carbon::now ();
		return view ( 'statement.incoming-invoice', $data );
	}
	
	/**
	 * Show a form to add a new statement for an cost declaration
	 * 
	 * @param PostReposity $repository        	
	 */
	public function costDeclaration(PostRepository $postRepository) {
		$data = $this->getDataForForm ( null, $postRepository );
		$data ['projects'] = Project::lists ( 'naam', 'id' );
		$data ['date'] = Carbon::now ();
		return view ( 'statement.cost-declaration', $data );
	}
	
	/**
	 * Creates a statement for an incoming invoice
	 * 
	 * @param Request $request        	
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function bookIncomingInvoice(Request $request) {
		$data = $request->all ();
		$supplier = Relation::find ( $data ['relatie_id'] );
		
		// Create statement with incoming data, and create a proper description
		$data ['omschrijving'] = 'Ontvangst factuur ' . $data ['factuurnummer'] . ' - ' . $supplier->bedrijfsnaam;
		$statement = Statement::create ( $data );
		
		// Handle invoice lines
		$sum = $this->handleLines ( $request->get ( 'Lines' ), $statement, $data ['project_id'] );
		$vat = $this->bookVAT ( $data ['btw'], $sum, $statement );
		
		// Create a new saldo to keep track of the payment for this invoice
		$saldo = Saldo::create ( [ 
				'relatie_id' => $data ['relatie_id'],
				'omschrijving' => 'Factuur ' . $data ['factuurnummer'] . ' - ' . $data ['opmerkingen'] 
		] );
		
		// Add total line
		$statement->addLine ( 1, $sum + $vat, AppConfig::get ( 'postCrediteuren' ), $saldo->id );
		
		Flash::message ( 'Incoming invoice has been booked' );
		
		return redirect ( 'statement' );
	}
	
	/**
	 * Creates a statement for a cost declaration
	 * 
	 * @param Request $request        	
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function bookCostDeclaration(Request $request) {
		$data = $request->all ();
		
		// Create statement with incoming data
		$statement = Statement::create ( $data );
		
		// Handle invoice lines
		$sum = $this->handleLines ( $request->get ( 'Lines' ), $statement, $data ['project_id'] );
		$vat = $this->bookVAT ( $data ['btw'], $sum, $statement );
		
		if ($data ['handling'] == 'pay') {
			// Create a new saldo to keep track of the payment for this invoice
			$saldo = Saldo::create ( [ 
					'relatie_id' => AppConfig::get ( 'relatiePrive' ),
					'omschrijving' => $data ['omschrijving'] 
			] );
			
			// Add total line
			$statement->addLine ( 1, $sum + $vat, AppConfig::get ( 'postCrediteuren' ), $saldo->id );
		} else {
			// Add total line, without saldo
			$statement->addLine ( 1, $sum + $vat, AppConfig::get ( 'postPriveInvesteringen' ) );
		}
		
		Flash::message ( 'Cost declaration has been booked' );
		
		return redirect ( 'statement' );
	}

	/**
	 * Stores the given lines with the statement
	 * 
	 * @param unknown $statement        	
	 */
	protected function handleLines($lines, $statement, $projectId = null) {
		// Store the lines as well.
		/*
		 * 40x Costs ...
		 * 40x Costs ...
		 * 180 Te vorderen BTW
		 * aan 140 Crebiteuren
		 */
		$sum = 0;
		foreach ( $lines as $lineInfo ) {
			$statementLine = $statement->addLine ( 0, $lineInfo ['amount'], $lineInfo ['post_id'] );
			
			if ($statementLine) {
				// Associate costs with project, if specified
				if ($projectId) {
					$statementLine->associateWithProject ( $projectId );
				}
				
				$sum += $lineInfo ['amount'];
			}
		}
		
		return $sum;
	}
	
	/**
	 * Stores the given lines with the statement
	 * 
	 * @param unknown $statement        	
	 */
	protected function bookVAT($percentage, $total, $statement) {
		if ($percentage == 0) {
			return 0;
		}
		
		$vat = round ( $total * ($percentage / 100), 2 );
		$statement->addLine ( 0, $vat, AppConfig::get ( 'postTeVorderenBTW' ) );
		
		return $vat;
	}
	
	/**
	 *
	 * @param Request $request        	
	 */
	protected function getFilterFromRequest(Request $request) {
		$postId = $request->input ( 'post_id' );
		$start = new Carbon ( $request->input ( 'start', Session::get ( 'start' ) ) );
		$end = new Carbon ( $request->input ( 'end', Session::get ( 'end' ) ) );
		
		// Build filter to use
		$filter = [ 
				'start' => $start,
				'end' => $end 
		];
		
		if ($postId)
			$filter ['post_id'] = $postId;
		
		return $filter;
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	protected function getDataForForm($statement, PostRepository $repository) {
		// Make sure to specify a set of lines with data
		$numLines = self::NUM_STATEMENT_LINES;
		if ($statement) {
			$numLines = max ( $numLines, count ( $statement->StatementLines ) );
		}
		
		// Specify enough empty lines
		$preEnteredLines = array_fill ( 0, $numLines, [ 
				'id' => null,
				'credit' => 0,
				'amount' => null,
				'post_id' => null 
		] );
		$sum = 0;
		
		// Overwrite the first lines with existing data
		if ($statement) {
			foreach ( $statement->StatementLines as $idx => $line ) {
				$preEnteredLines [$idx] = [ 
						'id' => $line->id,
						'credit' => $line->credit,
						'amount' => number_format ( $line->bedrag, 2, '.', '' ),
						'post_id' => $line->post_id 
				];
				$sum += $line->getSignedAmount ();
			}
		}
		
		// Add a list of posts to choose from
		$posts = $repository->getListForPostSelect ();
		$assets = Asset::lists ( 'omschrijving', 'id' );
		
		return compact ( 'statement', 'numLines', 'preEnteredLines', 'sum', 'posts', 'assets' );
	}
}
