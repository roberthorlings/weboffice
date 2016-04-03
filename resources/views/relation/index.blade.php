@extends('layouts/adminlte')
@section('page_title', "Relations")

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Relation</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover relations">
			            <thead>
			                <tr>
			                    <th>Type</th><th>Bedrijfsnaam</th><th>Contactpersoon</th><th>Plaats</th><th>Telefoon</th><th>E-mail</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($relation as $item)
			                <tr class="relation-type-{{$item->type}}">
			                    <td>{{ $item->getRelationType() }}</td>
			                    <td><a href="{{ url('relation', $item->id) }}">{{ $item->bedrijfsnaam }}</a></td>
			                    <td>{{ $item->contactpersoon }}</td>
			                    <td>{{ $item->plaats }}</td>
			                    <td>{{ $item->telefoon }}</td>
			                    <td>
			                    	@if($item->email)
			                    		<a href="mailto: {{ $item->email }}">{{ $item->email }}</a>
			                    	@endif
			                    </td>
			                    <td>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['relation', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
				                    	<div class="btn-group btn-group-xs">
					                        <a class="btn btn-default btn-xs" href="{{ url('relation/' . $item->id . '/edit') }}">
					                            <i class="fa fa-fw fa-pencil"></i>
					                        </a>
				                            {!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}
					                    </div>
			                        {!! Form::close() !!}
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $relation->appends(['filter' => $filter])->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ route('relation.index', [ 'filter' => 'active']) }}" class="btn btn-primary">Active customers</a>
                	<a href="{{ route('relation.index', [ 'filter' => 'all']) }}" class="btn btn-default">All relations</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Relation</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('relation/create') }}" class="btn btn-primary">Add New Relation</a><br /><br />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
