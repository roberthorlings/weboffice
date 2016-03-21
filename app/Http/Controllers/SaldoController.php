<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Weboffice\Models\Saldo;
use Flash;


class SaldoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
    	$includes = ['StatementLines', 'StatementLines.Statement', 'Relation'];
    	
        if( $request->get('filter') == 'all' ) {
    		$query = Saldo::with($includes);
        } else {
        	$query = Saldo::open()->with($includes);
        }
        
        $amounts = $query->orderBy('id', 'desc')->paginate(15);

        return view('saldo.index', ['amounts' => $amounts, 'filter' => $request->get('filter', 'open')]);
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

        Flash::message( 'Saldo added!');

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

        Flash::message( 'Saldo updated!');

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

        Flash::message( 'Saldo deleted!');

        return redirect('saldo');
    }

}
