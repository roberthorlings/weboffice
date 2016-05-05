@extends('layouts/adminlte')

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Post</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Nummer / Omschrijving</th><th>Type</th><th>Actions</th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($post as $item)
			                <tr>
			                    <td class="post-tree-depth-{{$item->depth}}">
			                    	@post($item)
			                    </td>
			                    <td>
			                    	{{$item->PostType->type}}
			                    </td>
			                    <td>
			                    	<div class="btn-group btn-group-xs">
				                        <a class="btn btn-default btn-xs" href="{{ url('post/' . $item->id . '/edit') }}">
				                            <i class="fa fa-fw fa-pencil"></i>
				                        </a>
				                        <a class="btn btn-default @if($item->isFirstInSubtree()) disabled @endif btn-xs" href="#" onClick="$(this).parents('td').find( 'form.move-up').submit(); return false;"><i class="fa fa-fw fa-arrow-up"></i></a>
				                        <a class="btn btn-default @if($item->isLastInSubtree()) disabled @endif  btn-xs" href="#" onClick="$(this).parents('td').find( 'form.move-down').submit(); return false;"><i class="fa fa-fw fa-arrow-down"></i></a>
				                        <a class="btn btn-danger btn-xs" href="#" onClick="$(this).parents('td').find( 'form.delete').submit(); return false;"><i class="fa fa-fw fa-trash"></i></a>
				                    </div>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['post', $item->id],
			                            'class' => 'delete',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
			                        {!! Form::close() !!}
			                        
			                        {!! Form::open([
			                            'method'=>'POST',
			                            'url' => route('post.moveUp', $item->id),
				                        'class' => 'move-up'
			                        ]) !!}
			                        {!! Form::close() !!}
			                        
			                        {!! Form::open([
			                            'method'=>'POST',
			                            'url' => route('post.moveDown', $item->id),
				                        'class' => 'move-down'
			                        ]) !!}
			                        {!! Form::close() !!}
			                        
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Post</h3>
                </div>
                <div class="box-body">
                	<a href="{{ url('post/create') }}" class="btn btn-primary btn-sm">Add New Post</a><br /><br />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Rebuild tree</h3>
                </div>
                <div class="box-body">
                	{!! Form::open(['route' => 'post.rebuild', 'class' => 'form-horizontal']) !!}
                		<button class="btn btn-primary" type="submit">Rebuild</button>
					{!! Form::close() !!}
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
