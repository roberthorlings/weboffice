<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Asset;

class AssetController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $asset = Asset::paginate(15);

        return view('asset.index', compact('asset'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["posten"] = \Weboffice\Post::all()->lists("description", "id");
    
        return view('asset.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Asset::create($request->all());

        Flash::message( 'Asset added!');

        return redirect('asset');
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
        $asset = Asset::findOrFail($id);

        return view('asset.show', compact('asset'));
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
        $asset = Asset::findOrFail($id);
    	$lists = [];
    	$lists["posten"] = \Weboffice\Post::all()->lists("description", "id");
    	
        return view('asset.edit', compact('lists', 'asset'));
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
        
        $asset = Asset::findOrFail($id);
        $asset->update($request->all());

        Flash::message( 'Asset updated!');

        return redirect('asset');
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
        Asset::destroy($id);

        Flash::message( 'Asset deleted!');

        return redirect('asset');
    }

}
