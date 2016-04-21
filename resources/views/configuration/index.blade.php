@extends('layouts/adminlte')

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Configuration</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	{!! Form::open(['route' => 'configuration.saveConfiguration']) !!}
			        <table class="table table-bordered table-striped table-hover configuration">
			            <tbody>
                			@foreach($categorizedConfiguration as $category => $items) 
	                			<tr><td colspan="3">
	                				{!! Form::button('<i class="fa fa-fw fa-save"></i>', ['class' => 'btn btn-sm btn-primary pull-right', 'type' => 'submit']) !!}
	                				<h4>{{$category}}</h4>
	                			</td></tr>
					            @foreach($items as $item)
					            	<?php $fieldName = 'configuration[' . $item->id . ']'; ?>
					                <tr>
					                    <td>{{ $item->title }}</td>
					                    <td>
					                    	@if($item->type == 'text')
					                    		{{ Form::text( $fieldName, $item->value, ['class' => 'form-control'] ) }}
					                    	@elseif($item->type == 'textarea')
					                    		{{ Form::textarea( $fieldName, $item->value, ['class' => 'form-control'] ) }}
					                    	@elseif($item->type == 'boolean')
					                    		{{ Form::select( $fieldName, [0 => 'Nee', 1 => 'Ja'], $item->value, ['class' => 'form-control'] ) }}
					                    	@elseif($item->type == 'post')
                								{!! Form::postSelect($fieldName, $posts, $item->value, ['class' => 'form-control']) !!} 
					                    	@elseif($item->type == 'relatie')
                								{!! Form::select($fieldName, $relations, $item->value, ['class' => 'form-control']) !!} 
					                    	@endif
				                    	</td>
					                    <td>
					                    	<div class="btn-group btn-group-xs">
						                        <a class="btn btn-default btn-xs" href="{{ url('configuration/' . $item->id . '/edit') }}">
						                            <i class="fa fa-fw fa-pencil"></i>
						                        </a>
						                    </div>
					                    </td>
					                </tr>
					            @endforeach
					        @endforeach
			            </tbody>
			        </table>
			        
                    {!! Form::button('<i class="fa fa-fw fa-save"> Save</i>', ['class' => 'btn btn-primary form-control', 'type' => 'submit']) !!}
			        {!! Form::close() !!}			        
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Configuration</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('configuration/create') }}" class="btn btn-primary btn-sm">Add New configuration item</a><br /><br />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
