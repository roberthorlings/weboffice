<?php
namespace Weboffice\Repositories;

use Weboffice\Post;

class PostRepository {

	/**
	 * Converts a list of posts into a grouped list of id => description
	 * @param array $rootNumbers 	List of root numbers to be included. If none is given, all roots are included 
	 */
	public function getListForPostSelect($rootNumbers = []) {
		$options = [];
		
		// Fetch roots
		$rootQuery = Post::roots()->orderBy('nummer');
		
		// Filter on numbers, if specified
		if(is_array($rootNumbers) && count($rootNumbers) > 0) {
			$rootQuery->whereIn("nummer", $rootNumbers);
		}
		
		// Loop through all roots, and add descendants
		foreach($rootQuery->get() as $root) {
			$branch = [];
			$descendants = $root->descendants()->orderBy('nummer')->get();
			foreach( $descendants as $descendant ) {
				$branch[$descendant->id] = $descendant->description;
			}
			$options[ $root->omschrijving ] = $branch;
		}
		
		return $options;
		
	}
}