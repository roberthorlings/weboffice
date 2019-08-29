<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Post;
use Weboffice\Models\Special;
use Weboffice\Repositories\PostRepository;

class SpecialController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$special = Special::paginate ( 15 );
		
		return view ( 'special.index', compact ( 'special' ) );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(PostRepository $repository) {
        $posts = $repository->getListForPostSelect ();
		return view ( 'special.create', compact( 'posts') );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		Special::create ( $request->all () );
		
		Flash::message ( 'Special added!' );
		
		return redirect ( 'special' );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function show($id) {
		$special = Special::findOrFail ( $id );
		
		return view ( 'special.show', compact ( 'special' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function edit($id, PostRepository $repository) {
		$special = Special::findOrFail ( $id );
        $posts = $repository->getListForPostSelect ();

		return view ( 'special.edit', compact ( 'special', 'posts' ) );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function update($id, Request $request) {
		$special = Special::findOrFail ( $id );
		$special->update ( $request->all () );
		
		Flash::message ( 'Special updated!' );
		
		return redirect ( 'special' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function destroy($id) {
		Special::destroy ( $id );
		
		Flash::message ( 'Special deleted!' );
		
		return redirect ( 'special' );
	}
}
