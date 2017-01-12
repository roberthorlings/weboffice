<?php

namespace Weboffice\Http\Controllers;

use AppConfig;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Session;
use Weboffice\Http\Controllers\Controller;
use Weboffice\Models\Account;
use Weboffice\Models\Post;
use Weboffice\Models\Saldo;
use Weboffice\Models\Statement;
use Weboffice\Models\Transaction;
use Weboffice\Repositories\PostRepository;
use Weboffice\Repositories\TransactionRepository;
use Weboffice\Import\Importer;

class TransactionController extends Controller {
	const NUM_STATEMENT_LINES = 6;
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$filter = $this->getFilterFromRequest ( $request );
		
		$query = Transaction::with ( [ 
				'Account',
				'Statement',
				'Statement.StatementLines',
				'Statement.StatementLines.Post' 
		] )->orderBy ( 'datum', 'desc' );
		
		// Apply filtering on date
		$query->where ( 'datum', '>=', $filter ['start'] );
		$query->where ( 'datum', '<=', $filter ['end'] );
		
		// Filter on project and relation as well
		if (array_key_exists ( 'post_id', $filter )) {
			$query->bookedOnPost ( $filter ['post_id'] );
		}
		
		$transaction = $query->paginate ( 15 );
		
		return view ( 'transaction.index', compact ( 'transaction', 'filter' ) );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$lists = [ ];
		$lists ["rekening_id"] = \Weboffice\Account::all ()->lists ( "description", "id" );
		
		return view ( 'transaction.create', compact ( 'lists' ) );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		$model = new Transaction ( $request->all () );
		
		if ($model->save ()) {
			Flash::message ( 'Transaction added!' );
		} else {
			Flash::error ( "Transaction not added, probably because it's a duplicate!" );
		}
		
		return redirect ( 'transaction' );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function show($id) {
		$transaction = Transaction::findOrFail ( $id );
		
		return view ( 'transaction.show', compact ( 'transaction' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function edit($id, PostRepository $repository) {
		$transaction = Transaction::with ( [ 
				'Statement',
				'Statement.StatementLines' 
		] )->findOrFail ( $id );
		return $this->editForm ( $transaction, $repository );
	}
	
	/**
	 * Assign the transaction to a booking as costs with VAT
	 * 
	 * @param unknown $id        	
	 * @param PostReposity $repository        	
	 */
	public function costs_with_vat($id, PostRepository $repository) {
		$transaction = Transaction::with ( [ 
				'Statement',
				'Statement.StatementLines' 
		] )->findOrFail ( $id );
		
		// Determine initial statement lines
		$amount = abs ( $transaction->bedrag );
		$vatPercentage = AppConfig::get ( 'btwPercentage' );
		$brutoAmount = round ( $amount / (1 + $vatPercentage / 100), 2 );
		$vatAmount = $amount - $brutoAmount;
		$initialLines = [ 
				[ 
						'id' => null,
						'amount' => $brutoAmount,
						'post_id' => null 
				],
				[ 
						'id' => null,
						'amount' => $vatAmount,
						'post_id' => AppConfig::get ( 'postTeVorderenBTW' ) 
				] 
		];
		
		return $this->editForm ( $transaction, $repository, $initialLines );
	}
	
	/**
	 * Assign the transaction to an invoice
	 * 
	 * @param unknown $id        	
	 * @param PostReposity $repository        	
	 */
	public function invoice($id) {
		$transaction = Transaction::with ( [ 
				'Statement' 
		] )->findOrFail ( $id );
		
		// Find any saldos still open
		$saldos = Saldo::open ()->with ( 'StatementLines' )->get ();
		;
		
		// Try to match the invoice based on the amount
		$match = $this->matchTransaction ( $saldos, $transaction );
		
		// Create a proper list of open saldos for the view
		$invoices = $saldos->lists ( 'omschrijving', 'id' );
		
		return view ( 'transaction.assign-invoice', [ 
				'transaction' => $transaction,
				'invoices' => $invoices,
				'selected_invoice_id' => $match ? $match ['id'] : null,
				'description' => $match ? 'Betaling ' . $match ['description'] : null 
		] );
	}
	
	/**
	 * Book the transaction as transfer
	 * 
	 * @param unknown $id        	
	 * @param PostReposity $repository        	
	 */
	public function transfer($id) {
		$transaction = Transaction::with ( [ 
				'Statement' 
		] )->findOrFail ( $id );
		
		// Find any saldos still open
		$saldos = Saldo::open ()->with ( 'StatementLines' )->get ();
		
		// Try to match the invoice based on the amount
		$match = $this->matchTransaction ( $saldos, $transaction );
		
		// Create a proper list of open saldos for the view
		$saldos = $saldos->lists ( 'omschrijving', 'id' );
		
		// Create a list of accounts
		$accounts = Account::where ( 'id', '<>', $transaction->rekening_id )->get ();
		
		// Find the selected account, based on the opposing account for the transaction
		$selected_account_id = null;
		foreach ( $accounts as $account ) {
			if ($account->rekeningnummer == $transaction->tegenrekening) {
				$selected_account_id = $account->id;
				break;
			}
		}
		
		return view ( 'transaction.assign-transfer', [ 
				'transaction' => $transaction,
				'accounts' => $accounts->lists ( 'description', 'id' ),
				'selected_account_id' => $selected_account_id,
				'saldos' => $saldos,
				'selected_saldo_id' => $match ? $match ['id'] : null 
		] );
	}
	
	/**
	 * Book the transaction as private booking
	 * 
	 * @param unknown $id        	
	 * @param PostReposity $repository        	
	 */
	public function private_transfer($id) {
		$transaction = Transaction::with ( [ 
				'Statement' 
		] )->findOrFail ( $id );
		
		return view ( 'transaction.assign-private', [ 
				'transaction' => $transaction,
				'description' => 'Privé boeking' 
		] );
	}
	
	/**
	 * Stores a transaction as invoice
	 * 
	 * @param unknown $id        	
	 * @param unknown $request        	
	 * @param unknown $transactionRepository        	
	 */
	public function store_invoice($id, Request $request, TransactionRepository $transactionRepository) {
		// Lookup the current transaction
		$transaction = Transaction::with ( 'Account' )->findOrFail ( $id );
		
		// Update or create the statement belonging to this transaction
		$statement = $transactionRepository->updateStatement ( $transaction, $request->get ( 'Statement' ) ['omschrijving'] );
		
		// Add a line that points to the saldo specified
		$saldoId = $request->get ( 'saldo_id' );
		
		// The line should be opposing the actual withdrawal or deposit
		$credit = ! $transaction->isCredited ();
		$postId = $credit ? AppConfig::get ( 'postDebiteuren' ) : AppConfig::get ( 'postCrediteuren' );
		$statement->addLine ( $credit, abs ( $transaction->bedrag ), $postId, $saldoId );
		
		// Redirect the user with a message
		Flash::message ( 'Transaction booked as invoice!' );
		return redirect ( 'transaction' );
	}
	
	/**
	 * Stores a transaction as transfer
	 * 
	 * @param unknown $id        	
	 * @param unknown $request        	
	 * @param unknown $transactionRepository        	
	 */
	public function store_transfer($id, Request $request, TransactionRepository $transactionRepository) {
		// Lookup the current transaction
		$transaction = Transaction::with ( 'Account' )->findOrFail ( $id );
		$opposingAccount = Account::find ( $request->get ( 'account_id' ) );
		$currentAccount = $transaction->Account;
		
		// Determine descriptions
		if ($transaction->isCredited ()) {
			$description = "Overboeking naar " . $opposingAccount->omschrijving;
			$saldo_description = "Overboeking " . number_format ( abs ( $transaction->bedrag ), 2 ) . " van " . $currentAccount->omschrijving . " naar " . $opposingAccount->omschrijving;
		} else {
			$description = "Overboeking van " . $opposingAccount->omschrijving;
			$saldo_description = "Overboeking " . number_format ( abs ( $transaction->bedrag ), 2 ) . " van " . $opposingAccount->omschrijving . " naar " . $currentAccount->omschrijving;
		}
		
		// Update or create the statement belonging to this transaction
		$statement = $transactionRepository->updateStatement ( $transaction, $description );
		
		// Check whether there is a saldo
		$saldoId = $request->get ( 'saldo_id' );
		if ($saldoId) {
			$saldo = Saldo::find ( $saldoId );
		} else {
			$saldo = new Saldo ();
			$saldo->omschrijving = $saldo_description;
			$saldo->relatie_id = AppConfig::get ( 'relatieSelf' );
			$saldo->save ();
		}
		
		// The line should be opposing the actual withdrawal or deposit
		$credit = ! $transaction->isCredited ();
		$postId = AppConfig::get ( 'postTussenrekening' );
		$statement->addLine ( $credit, abs ( $transaction->bedrag ), $postId, $saldo->id );
		
		// Redirect the user with a message
		Flash::message ( 'Transaction booked as transfer!' );
		return redirect ( 'transaction' );
	}
	
	/**
	 * Stores a transaction as private transfer
	 * 
	 * @param unknown $id        	
	 * @param unknown $request        	
	 * @param unknown $transactionRepository        	
	 */
	public function store_private($id, Request $request, TransactionRepository $transactionRepository) {
		// Lookup the current transaction
		$transaction = Transaction::with ( 'Account' )->findOrFail ( $id );
		
		// Update or create the statement belonging to this transaction
		$statement = $transactionRepository->updateStatement ( $transaction, $request->get ( 'Statement' ) ['omschrijving'] );
		
		// The line should be opposing the actual withdrawal or deposit
		$credit = ! $transaction->isCredited ();
		$postId = $credit ? AppConfig::get ( 'postPriveInvesteringen' ) : AppConfig::get ( 'postPriveOpnames' );
		$statement->addLine ( $credit, abs ( $transaction->bedrag ), $postId );
		
		// Redirect the user with a message
		Flash::message ( 'Transaction booked as private transfer!' );
		return redirect ( 'transaction' );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function update($id, Request $request, TransactionRepository $transactionRepository) {
		// Lookup the current transaction
		$transaction = Transaction::with ( 'Account' )->findOrFail ( $id );
		
		// Update or create the statement belonging to this transaction
		$statement = $transactionRepository->updateStatement ( $transaction, $request->get ( 'Statement' ) ['omschrijving'] );
		
		// Store the lines as well.
		$linesToSave = [ ];
		foreach ( $request->get ( 'Statement' ) ['lines'] as $lineInfo ) {
			// The rest of the lines are used to counter the actual transactionline for the deposit or withdrawal
			// For that reason, the sign should be opposed to the actual transaction
			$credit = ! $transaction->isCredited ();
			
			$statement->updateLine ( $lineInfo ['id'], $credit, $lineInfo ['amount'], $lineInfo ['post_id'] );
		}
		
		Flash::message ( 'Transaction updated!' );
		
		return redirect ( 'transaction' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function destroy($id) {
		Transaction::destroy ( $id );
		
		Flash::message ( 'Transaction deleted!' );
		
		return redirect ( 'transaction' );
	}
	
	/**
	 * Remove the statement for the specified resource
	 * 
	 * @param unknown $id        	
	 */
	public function deleteStatement($id) {
		$transaction = Transaction::with ( 'Statement' )->findOrFail ( $id );
		
		// Remove the belonging statement
		if ($transaction->Statement) {
			$transaction->Statement->delete ();
		}
		
		// Mark the transaction as not being booked
		$transaction->ingedeeld = false;
		$transaction->save ();
		
		// Redirect
		Flash::message ( 'Statement has been deleted' );
		return redirect ( 'transaction' );
	}
	
	/**
	 * Shows a form for the user to import data
	 */
	public function import() {
		return view ( 'transaction.import' );
	}
	
	/**
	 * Performs the actual import
	 * 
	 * @return number
	 */
	public function storeImport(Request $request) {
		$file = $request->file ( 'transactions' );
		
		if (! $file->isValid ()) {
			Flash::error ( 'No or invalid file was uploaded for import.' );
			return redirect ( 'transaction/import' );
		}
		
		// Determine whether we can import this file
		$importer = Importer::getImporter ( $file );
		
		if (! $importer) {
			Flash::error ( "The uploaded file was not recognized as one of the supported file types (ABN, ING or Rabo). No transactions were imported." );
			return redirect ( 'transaction/import' );
		}
		
		// Parse the file
		$transactions = $importer->parse ();
		
		if (count ( $transactions ) == 0) {
			Flash::warning ( "The uploaded file is supported, but did not contain any transaction." );
			return redirect ( 'transaction/import' );
		}
		
		// Store transactions in database
		$results = $this->storeTransactions ( $transactions );
		
		// Fetch account data for results
		$accountList = Account::whereIn ( "id", array_keys ( $results ) )->get ();
		$accounts = [ ];
		foreach ( $accountList as $account ) {
			$accounts [$account->id] = $account;
		}
		
		return view ( 'transaction.import-results', compact ( 'results', 'accounts' ) );
	}
	
	/**
	 * Stores newly created/imported transactions
	 * 
	 * @param array $transactions        	
	 */
	protected function storeTransactions($transactions) {
		$initialResult = [ 
				"total" => 0,
				"succesful" => 0,
				"existing" => 0 
		];
		
		$results = [ ];
		
		foreach ( $transactions as $transaction ) {
			if (! array_key_exists ( $transaction->rekening_id, $results )) {
				$results [$transaction->rekening_id] = $initialResult;
			}
			
			$results [$transaction->rekening_id] ["total"] ++;
			if ($transaction->save ()) {
				$results [$transaction->rekening_id] ["succesful"] ++;
			} else {
				$results [$transaction->rekening_id] ["existing"] ++;
			}
		}
		
		return $results;
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	protected function editForm(Transaction $transaction, PostRepository $repository, $initialLines = []) {
		$statement = $transaction->ingedeeld && $transaction->Statement ? $transaction->Statement : null;
		
		// Make sure to specify a set of lines with data
		$numLines = self::NUM_STATEMENT_LINES;
		if ($statement) {
			$numLines = max ( $numLines, count ( $statement->StatementLines ) );
		}
		
		// Specify enough empty lines
		$preEnteredLines = array_fill ( 0, $numLines, [ 
				'id' => null,
				'amount' => null,
				'post_id' => null 
		] );
		$sum = 0;
		
		// Overwrite the first lines with existing data
		if ($statement) {
			foreach ( $statement->StatementLines as $idx => $line ) {
				// Skip the first line, as it is the line that indicates the
				// withdrawal or deposito on the account itself
				if ($idx == 0)
					continue;
				
				$preEnteredLines [$idx - 1] = [ 
						'id' => $line->id,
						'amount' => number_format ( $line->bedrag, 2, '.', '' ),
						'post_id' => $line->post_id 
				];
				$sum += $line->bedrag;
			}
		} else if (count ( $initialLines ) > 0) {
			foreach ( $initialLines as $idx => $line ) {
				$preEnteredLines [$idx] = $line;
				$sum += $line ['amount'];
			}
		}
		
		// Add a list of posts to choose from
		$posts = $repository->getListForPostSelect ();
		
		return view ( 'transaction.edit', compact ( 'transaction', 'statement', 'numLines', 'preEnteredLines', 'sum', 'posts' ) );
	}
	
	/**
	 * Searches the saldos to find one matching the given transaction.
	 * Match is done based on the amount
	 * 
	 * @param unknown $saldos        	
	 * @param unknown $transaction        	
	 */
	protected function matchTransaction($saldos, $transaction) {
		$selected_invoice_id = null;
		$description = '';
		
		$amountToFind = abs ( $transaction->bedrag );
		foreach ( $saldos as $saldo ) {
			if ($amountToFind == abs ( $saldo->getOpenAmount () )) {
				return [ 
						'id' => $saldo->id,
						'description' => $saldo->omschrijving 
				];
			}
		}
		return null;
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
}
