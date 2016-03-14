<?php

namespace Weboffice\Http\Controllers;

use AppConfig;
use Flash;
use Illuminate\Http\Request;
use Weboffice\Http\Controllers\Controller;
use Weboffice\Post;
use Weboffice\Repositories\PostRepository;
use Weboffice\Repositories\TransactionRepository;
use Weboffice\Saldo;
use Weboffice\Statement;
use Weboffice\Transaction;

class TransactionController extends Controller
{
	const NUM_STATEMENT_LINES = 6;
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $transaction = Transaction::with(['Statement', 'Statement.StatementLines', 'Statement.StatementLines.Post'])->paginate(15);

        return view('transaction.index', compact('transaction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["rekening_id"] = \Weboffice\Account::all()->lists("description", "id");
    	
        return view('transaction.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Transaction::create($request->all());

        Flash::message( 'Transaction added!');

        return redirect('transaction');
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
        $transaction = Transaction::findOrFail($id);

        return view('transaction.show', compact('transaction'));
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
    	$transaction = Transaction::with(['Statement', 'Statement.StatementLines'])->findOrFail($id);
    	return $this->editForm($transaction, $repository);
    }
    
    /**
     * Assign the transaction to a booking as costs with VAT
     * @param unknown $id
     * @param PostReposity $repository
     */
    public function costs_with_vat($id, PostRepository $repository) {
    	$transaction = Transaction::with(['Statement', 'Statement.StatementLines'])->findOrFail($id);
    	
    	// Determine initial statement lines
    	$amount = abs($transaction->bedrag);
    	$vatPercentage = AppConfig::get('btwPercentage');
    	$brutoAmount = round($amount / (1 + $vatPercentage / 100), 2);
    	$vatAmount = $amount - $brutoAmount;
    	$initialLines = [
    		[ 'id' => null, 'amount' => $brutoAmount, 'post_id' => null],
    		[ 'id' => null, 'amount' => $vatAmount, 'post_id' => AppConfig::get('postTeVorderenBTW')],
    	];
    	
    	return $this->editForm($transaction, $repository, $initialLines);
    }
    
    /**
     * Assign the transaction to an invoice
     * @param unknown $id
     * @param PostReposity $repository
     */
    public function invoice($id) {
    	$transaction = Transaction::with(['Statement'])->findOrFail($id);
    	$statement = $transaction->ingedeeld && $transaction->Statement ? $transaction->Statement : null;
    	 
    	// Find any saldos still open
    	$saldos = Saldo::open()->with('StatementLines')->get();;
    	
    	// Try to match the invoice based on the amount
    	$selected_invoice_id = null;
    	$amountToFind = abs($transaction->bedrag);
    	foreach( $saldos as $saldo ) {
    		if( $amountToFind == $saldo->getOpenAmount() ){
    			$selected_invoice_id = $saldo->id;
    			break;
    		}
    	}
    	
    	// Create a proper list of open saldos for the view
    	$invoices = $saldos->lists( 'omschrijving', 'id' );
    	
    	return view('transaction.assign-invoice', compact('transaction', 'invoices', 'selected_invoice_id', 'statement'));    	
    }
    
    /**
     * Stores a transaction as invoice
     * @param unknown $id
     * @param unknown $request
     * @param unknown $transactionRepository
     */
    public function store_invoice($id, Request $request, TransactionRepository $transactionRepository ) {
    	// Lookup the current transaction
    	$transaction = Transaction::with('Account')->findOrFail($id);
    	
    	// Update or create the statement belonging to this transaction
    	$statement = $transactionRepository->updateStatement($transaction, $request->get('Statement')['omschrijving']);
    	
    	// Add a line that points to the saldo specified
    	$saldoId = $request->get('saldo_id');
        
    	// The line should be opposing the actual withdrawal or deposit
    	$credit = !$transaction->isCredited();
    	$postId = $credit ? AppConfig::get('postDebiteuren') : AppConfig::get('postCrediteuren');
    	$statement->addLine($credit, abs($transaction->bedrag), $postId, $saldoId);
    	
    	// Redirect the user with a message
    	Flash::message( 'Transaction booked as invoice!');
    	return redirect('transaction');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request, TransactionRepository $transactionRepository )
    {
        // Lookup the current transaction
        $transaction = Transaction::with('Account')->findOrFail($id);

        // Update or create the statement belonging to this transaction 
        $statement = $transactionRepository->updateStatement($transaction, $request->get('Statement')['omschrijving']);
        
        // Store the lines as well.
        $linesToSave = [];
        foreach( $request->get('Statement')['lines'] as $lineInfo ) {
        	// The rest of the lines are used to counter the actual transactionline for the deposit or withdrawal
        	// For that reason, the sign should be opposed to the actual transaction 
        	$credit = !$transaction->isCredited();
        	
        	$statement->updateLine($lineInfo['id'], $credit, $lineInfo['amount'], $lineInfo['post_id']);
        }
        
        Flash::message( 'Transaction updated!');

        return redirect('transaction');
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
        Transaction::destroy($id);

        Flash::message( 'Transaction deleted!');

        return redirect('transaction');
    }
    
    /**
     * Remove the statement for the specified resource
     * @param unknown $id
     */
    public function deleteStatement($id)
    {
    	$transaction = Transaction::with('Statement')->findOrFail($id);
    	
    	// Remove the belonging statement
    	if($transaction->Statement) {
    		$transaction->Statement->delete();
    	}
    	
    	// Mark the transaction as not being booked
    	$transaction->ingedeeld = false;
    	$transaction->save();
    	
    	// Redirect
    	Flash::message( 'Statement has been deleted');
    	return redirect('transaction');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    protected function editForm(Transaction $transaction, PostRepository $repository, $initialLines = [])
    {
    	$statement = $transaction->ingedeeld && $transaction->Statement ? $transaction->Statement : null;
    
    	// Make sure to specify a set of lines with data
    	$numLines = self::NUM_STATEMENT_LINES;
    	if( $statement ) {
    		$numLines = max($numLines, count($statement->StatementLines));
    	}
    
    	// Specify enough empty lines
    	$preEnteredLines = array_fill(0, $numLines, [ 'id' => null, 'amount' => null, 'post_id' => null ]);
    	$sum = 0;
    
    	// Overwrite the first lines with existing data
    	if( $statement ) {
    		foreach( $statement->StatementLines as $idx => $line ) {
    			// Skip the first line, as it is the line that indicates the
    			// withdrawal or deposito on the account itself
    			if( $idx == 0 )
    				continue;
    
    				$preEnteredLines[$idx - 1] = [ 'id' => $line->id, 'amount' => number_format($line->bedrag, 2, '.', ''), 'post_id' => $line->post_id ];
    				$sum += $line->bedrag;
    		}
    	} else if( count($initialLines) > 0 ) {
    		foreach( $initialLines as $idx => $line ) {
    			$preEnteredLines[$idx] = $line;
    			$sum += $line['amount'];
    		}
    	}
    
    	// Add a list of posts to choose from
    	$posts = $repository->getListForPostSelect();
    	 
    	return view('transaction.edit', compact('transaction', 'statement', 'numLines', 'preEnteredLines', 'sum',  'posts'));
    }
    

}
