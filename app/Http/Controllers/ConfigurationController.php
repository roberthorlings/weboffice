<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Configuration;
use Weboffice\Repositories\PostRepository;
use Weboffice\Models\Relation;

class ConfigurationController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(PostRepository $postRepository) {
		// Determine currnet configuration
		$configuration = Configuration::orderBy ( 'categorie_volgorde' )->get ();
		$categorizedConfiguration = [ ];
		
		foreach ( $configuration as $item ) {
			if (! array_key_exists ( $item->categorie, $categorizedConfiguration )) {
				$categorizedConfiguration [$item->categorie] = [ ];
			}
			
			$categorizedConfiguration [$item->categorie] [] = $item;
		}
		
		// Determine lists to choose from
		$posts = $postRepository->getListForPostSelect ();
		$relations = Relation::lists ( 'bedrijfsnaam', 'id' );
		
		return view ( 'configuration.index', compact ( 'categorizedConfiguration', 'posts', 'relations' ) );
	}
	public function saveAll(Request $request) {
		$updatedConfiguration = $request->get ( 'configuration', [ ] );
		
		foreach ( $updatedConfiguration as $id => $newValue ) {
			Configuration::where ( 'id', '=', $id )->update ( [ 
					'value' => $newValue 
			] );
		}
		
		Flash::message ( 'Configuration updated!' );
		return redirect ( 'configuration' );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$lists = [ ];
		
		return view ( 'configuration.create', compact ( 'lists' ) );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		Configuration::create ( $request->all () );
		
		Flash::message ( 'Configuration item added!' );
		
		return redirect ( 'configuration' );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function show($id) {
		$configuration = Configuration::findOrFail ( $id );
		
		return view ( 'configuration.show', compact ( 'configuration' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function edit($id) {
		$configuration = Configuration::findOrFail ( $id );
		$lists = [ ];
		
		return view ( 'configuration.edit', compact ( 'lists', 'configuration' ) );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function update($id, Request $request) {
		$configuration = Configuration::findOrFail ( $id );
		$configuration->update ( $request->all () );
		
		Flash::message ( 'Configuration item updated!' );
		
		return redirect ( 'configuration' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function destroy($id) {
		Configuration::destroy ( $id );
		
		Flash::message ( 'Configuration deleted!' );
		
		return redirect ( 'configuration' );
	}
}
