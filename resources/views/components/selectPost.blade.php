@inject('relationRepo', 'Weboffice\Repositories\PostRepository')
<?php 
	$attributes[ 'class' ] = ( $attributes[ 'class' ] ?: '' ) . ' post-select';
	
	if(!$posts)
		$posts = $relationRepo->getListForPostSelect();
?>
{!! Form::select($name, $posts, $value, $attributes) !!}
