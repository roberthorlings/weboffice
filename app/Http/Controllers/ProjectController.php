<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Project;
use Weboffice\Models\Relation;
use Weboffice\Repositories\PostRepository;

class ProjectController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$includes = [ 
				'Post',
				'Relation' 
		];
		
		if ($request->get ( 'filter' ) == 'all') {
			$query = Project::with ( $includes );
		} else {
			$query = Project::active ()->with ( $includes );
		}
		
		$project = $query->orderBy ( 'created_at', 'desc' )->orderBy ( 'id', 'desc' )->paginate ( 15 );
		
		return view ( 'project.index', [ 
				'project' => $project,
				'filter' => $request->get ( 'filter' ) 
		] );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(PostRepository $postRepository) {
		$relations = Relation::pluck ( 'bedrijfsnaam', 'id' );
		$posts = $postRepository->getListForPostSelect ();
		$statuses = $this->getStatuses ();
		
		return view ( 'project.create', compact ( 'relations', 'posts', 'statuses' ) );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		Project::create ( $request->all () );
		
		Flash::message ( 'Project added!' );
		
		return redirect ( 'project' );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function show($id) {
		$project = Project::findOrFail ( $id );
		
		return view ( 'project.show', compact ( 'project' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function edit($id, PostRepository $postRepository) {
		$project = Project::findOrFail ( $id );
		
		$relations = Relation::pluck ( 'bedrijfsnaam', 'id' );
		$posts = $postRepository->getListForPostSelect ();
		$statuses = $this->getStatuses ();
		
		return view ( 'project.edit', compact ( 'project', 'relations', 'posts', 'statuses' ) );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function update($id, Request $request) {
		$project = Project::findOrFail ( $id );
		$project->update ( $request->all () );
		
		Flash::message ( 'Project updated!' );
		
		return redirect ( 'project' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function destroy($id) {
		Project::destroy ( $id );
		
		Flash::message ( 'Project deleted!' );
		
		return redirect ( 'project' );
	}
	protected function getStatuses() {
		return [ 
				Project::STATUS_NIETBEGONNEN => 'Not started',
				Project::STATUS_OFFERTEVERSTUURD => 'Quote sent',
				Project::STATUS_ACTIEF => 'Active',
				Project::STATUS_FACTUURVERSTUURD => 'Invoice sent',
				Project::STATUS_AFGEROND => 'Finished',
				Project::STATUS_OFFERTEAFGEWEZEN => 'Quote rejected' 
		];
	}
}
