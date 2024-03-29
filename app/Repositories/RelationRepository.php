<?php
namespace Weboffice\Repositories;

use Weboffice\Models\Relation;
use Weboffice\Models\Project;

class RelationRepository {
	/**
	 * Returns a list for relation project select
	 */
	public function getRelationsWithProjects($relationQueryBuilder = null, $projectQueryBuilder = null) {
		// Determine whether to filter or order the projects
		if($projectQueryBuilder) {
			$builder = Relation::with(['Projects' => $projectQueryBuilder]);
		} else {
			$builder = Relation::with('Projects');
		}
		
		// Determine whether to filter or order the relations
		if( $relationQueryBuilder ) {
			$builder = $relationQueryBuilder($builder);
		}
		
		return $builder->get();
	}
	
	/**
	 * Returns the relations (including projects) that can be used for working hour entry
	 */
	public function getRelationsForWorkingHourEntry() {
		return $this->getRelationsWithProjects(
			function($builder) {
				return $builder->where( 'werktijd', true );
			},
			function($builder) {
				return $builder->where('status', Project::STATUS_ACTIEF );
			}
		);		
	}

	/**
	 * Converts a list 
	 * @param unknown $relations
	 */
	public function convertToListForProjectSelect($relations) {
		$options = [];
		
		foreach($relations as $relation) {
			$options['klant.' . $relation->id] = $relation->bedrijfsnaam;
			foreach($relation->Projects as $project) {
				$options['project.' . $relation->id . '.' . $project->id] = $project->naam;
			}
		
		}
		
		return $options;
		
	}
}