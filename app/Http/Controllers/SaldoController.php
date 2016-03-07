<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Requests;
use Weboffice\Http\Controllers\Controller;

use Weboffice\Saldo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class SaldoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $saldo = Saldo::paginate(15);

        return view('saldo.index', compact('saldo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Relation::lists("bedrijfsnaam", "id");
    
        return view('saldo.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Saldo::create($request->all());

        Session::flash('flash_message', 'Saldo added!');

        return redirect('saldo');
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
        $saldo = Saldo::findOrFail($id);

        return view('saldo.show', compact('saldo'));
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
        $saldo = Saldo::findOrFail($id);
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Relation::lists("bedrijfsnaam", "id");

        return view('saldo.edit', compact('lists', 'saldo'));
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
        
        $saldo = Saldo::findOrFail($id);
        $saldo->update($request->all());

        Session::flash('flash_message', 'Saldo updated!');

        return redirect('saldo');
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
        Saldo::destroy($id);

        Session::flash('flash_message', 'Saldo deleted!');

        return redirect('saldo');
    }

}
