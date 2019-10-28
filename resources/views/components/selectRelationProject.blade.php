@inject('relationRepo', 'Weboffice\Repositories\RelationRepository')
<?php 
	$attributes[ 'class' ] = ( $attributes[ 'class' ] ?: '' ) . ' relation-project-select';
	$relations = $relationRepo->convertToListForProjectSelect($relations);
	
	// Indent projects
	foreach($relations as $key => $title) {
		if(\Illuminate\Support\Str::StartsWith($key, 'project'))
			$relations[$key] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $title;
	}
	
	// Parse value if given
	$relatieId = $projectId = null;
	
	if($value) {
		$parts = explode(".", $value);
		if($parts[0] == "klant" && count($parts) > 1) {
			$relatieId = $parts[1];
		}
		if($parts[0] == "project" && count($parts) > 2) {
			$relatieId = $parts[1];
			$projectId = $parts[2];
		}
					
	}
?>
{!! Form::select($name, $relations, $value, $attributes) !!}

{!! Form::hidden('relatie_id', $relatieId, [ 'class' => 'hidden-relation-' . $name . '-relatie-id']) !!}
{!! Form::hidden('project_id', $projectId, [ 'class' => 'hidden-relation-' . $name . '-project-id']) !!}
