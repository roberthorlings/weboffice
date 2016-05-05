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
        $post = Post::with('PostType')->orderBy('lft')->get();

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
    	$lists["post_type_id"] = \Weboffice\Models\PostType::lists("type", "id");
    	$lists["parent_id"] = Post::all()->lists("description", "id");
    	 
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
        
        // Create the new post itself
        $post = Post::create($request->except('parent_id'));

        // Attach to parent (if requested)
        $parent = Post::find($request->get("parent_id"));
        if($parent) {
        	$post->makeChildOf($parent);
        }
        
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
    	$lists["post_type_id"] = \Weboffice\Models\PostType::lists("type", "id");
    	$lists["parent_id"] = Post::all()->lists("description", "id");

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
        $post->update($request->except('parent_id'));

        // Attach to parent (if requested)
        $newParentId = $request->get('parent_id');
        if($newParentId != $post->parent_id) {
        	$parent = Post::find($newParentId);
        	if($parent) {
        		$post->makeChildOf($parent);
        	} else {
        		$post->makeRoot();
        	}
        }
        
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
    
    /**
     * Rebuilds the full tree
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function rebuild() {
    	Post::rebuild();
    	
    	Flash::message( 'Tree of posts has been rebuilt');
    	return redirect('post');
    }
    
    /**
     * Moves the given post up the tree
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function moveUp($id)
    {
    	$post = Post::findOrFail($id);
    	
    	// Check if the item can be moved
    	if( $post->isFirstInSubtree() ) {
    		Flash::warning('Post could not be moved up, as it is the first in its subtree');
    	} else {
	    	$post->moveLeft();
	    	Flash::message( 'Post moved!');
    	}
    	
    	return redirect('post');
    }

    /**
     * Moves the given post down the tree
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function moveDown($id)
    {
    	$post = Post::findOrFail($id);

    	// Check if the item can be moved
    	if( $post->isLastInSubtree() ) {
    		Flash::warning('Post could not be moved down, as it is the last in its subtree');
    	} else {
    		$post->moveRight();
    		Flash::message( 'Post moved!');
    	}
    	 
    	return redirect('post');
    }
    

}
