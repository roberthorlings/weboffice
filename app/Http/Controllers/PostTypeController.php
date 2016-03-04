<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Requests;
use Weboffice\Http\Controllers\Controller;

use Weboffice\PostType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class PostTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $posttype = PostType::paginate(15);

        return view('posttype.index', compact('posttype'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('posttype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        PostType::create($request->all());

        Session::flash('flash_message', 'PostType added!');

        return redirect('posttype');
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
        $posttype = PostType::findOrFail($id);

        return view('posttype.show', compact('posttype'));
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
        $posttype = PostType::findOrFail($id);

        return view('posttype.edit', compact('posttype'));
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
        
        $posttype = PostType::findOrFail($id);
        $posttype->update($request->all());

        Session::flash('flash_message', 'PostType updated!');

        return redirect('posttype');
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
        PostType::destroy($id);

        Session::flash('flash_message', 'PostType deleted!');

        return redirect('posttype');
    }

}
