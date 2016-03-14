<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Weboffice\Transaction;
use Weboffice\Post;
use Weboffice\Statement;
use Weboffice\StatementLine;
use Illuminate\Http\Request;
use Flash;
use Weboffice\Repositories\PostRepository;

class TransactionController extends Controller
{

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
        $statement = $transaction->ingedeeld && $transaction->Statement ? $transaction->Statement : null;
        
        // Make sure to specify a set of lines with data
        $numLines = 6;
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
        }
        
        // Add a list of posts to choose from
        $posts = $repository->getListForPostSelect();
        	
        return view('transaction.edit', compact('transaction', 'statement', 'numLines', 'preEnteredLines', 'sum',  'posts'));
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
        // Lookup the current transaction
        $transaction = Transaction::with('Account')->findOrFail($id);
        
        // If there is no statement yet, add it, otherwise reuse the existing object
        if( !$transaction->Statement ) {
        	// Mark transaction as being booked
        	$transaction->ingedeeld = 1;
        	$transaction->save();
        	
        	$statement = new Statement();
        	$statement->omschrijving = $request->get('Statement')['omschrijving'];
        	$statement->datum = $transaction->datum;
        	$transaction->Statement()->save($statement);
        	
        	// Make sure the add the first statement line
        	$line = new StatementLine();
        	$line->credit = $transaction->bedrag < 0;	// Credit if amount < 0, Debet otherwise
        	$line->bedrag = abs($transaction->bedrag);
        	$line->post_id = $transaction->Account->post_id;
        	$statement->StatementLines()->save($line);
        } else {
        	$statement = $transaction->Statement;
        	$statement->omschrijving = $request->get('Statement')['omschrijving'];
        	$statement->save();
        }
        
        // Store the lines as well.
        $linesToSave = [];
        foreach( $request->get('Statement')['lines'] as $lineInfo ) {
        	// If ID is specified, reuse existing line
        	if( $lineInfo['id'] ) {
        		$line = StatementLine::find($lineInfo['id']);
        	} else {
        		$line = new StatementLine();
        		$line->credit = $transaction->bedrag >= 0;	// Opposing lines should be credit is amount >= 0
        	}
        	
        	// If no amount is specified, don't store anything (or delete existing)
        	if( !$lineInfo[ 'amount' ] ) {
        		if($line->id)
        			$line->delete();
        		
        		continue;
        	}

        	// Update line properites
        	$line->bedrag = $lineInfo['amount'];
        	$line->post_id = $lineInfo['post_id'];
        	
        	$linesToSave[] = $line;
        }
        
        // Save all new lines at once
        $statement->StatementLines()->saveMany($linesToSave);
        
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

}
