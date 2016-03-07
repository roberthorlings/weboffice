@inject('relationRepo', 'Weboffice\Repositories\RelationRepository')
<?php 
	$attributes[ 'class' ] = ( $attributes[ 'class' ] ?: '' ) . ' relation-project-select';
	$relations = $relationRepo->convertToListForProjectSelect($relations);
	
	// Indent projects
	foreach($relations as $key => $title) {
		if(starts_with($key, 'project'))
			$relations[$key] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $title;
	}
?>
{!! Form::select($name, $relations, $value, $attributes) !!}

{!! Form::hidden('relatie_id', null, [ 'class' => 'hidden-relation-' . $name . '-relatie-id']) !!}
{!! Form::hidden('project_id', null, [ 'class' => 'hidden-relation-' . $name . '-project-id']) !!}
