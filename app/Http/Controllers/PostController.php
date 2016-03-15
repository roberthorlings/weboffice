<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Post;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $post = Post::paginate(15);

        return view('post.index', compact('post'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["post_type_id"] = \Weboffice\PostType::lists("type", "id");
    
        return view('post.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['nummer' => 'required', 'omschrijving' => 'required', ]);

        Post::create($request->all());

        Flash::message( 'Post added!');

        return redirect('post');
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
        $post = Post::findOrFail($id);

        return view('post.show', compact('post'));
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
        $post = Post::findOrFail($id);
    	$lists = [];
    	$lists["post_type_id"] = \Weboffice\PostType::lists("type", "id");

        return view('post.edit', compact('lists', 'post'));
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
        $this->validate($request, ['nummer' => 'required', 'omschrijving' => 'required', ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        Flash::message( 'Post updated!');

        return redirect('post');
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
        Post::destroy($id);

        Flash::message( 'Post deleted!');

        return redirect('post');
    }

}
