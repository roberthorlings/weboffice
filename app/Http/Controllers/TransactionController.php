<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Requests;
use Weboffice\Http\Controllers\Controller;

use Weboffice\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

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

        Session::flash('flash_message', 'Transaction added!');

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
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
    	$lists = [];
    	$lists["rekening_id"] = \Weboffice\Account::all()->lists("description", "id");

        return view('transaction.edit', compact('lists', 'transaction'));
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
        
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());

        Session::flash('flash_message', 'Transaction updated!');

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

        Session::flash('flash_message', 'Transaction deleted!');

        return redirect('transaction');
    }

}
