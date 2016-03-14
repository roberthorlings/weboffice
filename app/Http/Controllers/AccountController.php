<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Account;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $account = Account::paginate(15);

        return view('account.index', compact('account'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["post_id"] = \Weboffice\Post::all()->lists("description", "id");
    
        return view('account.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['rekeningnummer' => 'required', 'omschrijving' => 'required', ]);

        Account::create($request->all());

        Flash::message( 'Account added!');

        return redirect('account');
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
        $account = Account::findOrFail($id);

        return view('account.show', compact('account'));
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
        $account = Account::findOrFail($id);
    	$lists = [];
    	$lists["post_id"] = \Weboffice\Post::all()->lists("description", "id");

        return view('account.edit', compact('lists', 'account'));
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
        $this->validate($request, ['rekeningnummer' => 'required', 'omschrijving' => 'required', ]);

        $account = Account::findOrFail($id);
        $account->update($request->all());

        Flash::message( 'Account updated!');

        return redirect('account');
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
        Account::destroy($id);

        Flash::message( 'Account deleted!');

        return redirect('account');
    }

}
