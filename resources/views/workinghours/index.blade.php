@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/workinghours.index.js'))}}
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Workinghours</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Datum</th><th>Duur</th><th>Klant</th><th>Opmerkingen</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($workinghours as $item)
			                <tr>
			                    <td>
			                    	{{ $item->begintijd->format('d-m / H:i') }} - {{ $item->eindtijd->format('H:i') }} 
			                    </td>
			                    <td>
			                    	{{ $item->duration->format("%H:%I") }}
			                    </td>
								<td><span class="relatie">{{ $item->relation ? $item->relation->bedrijfsnaam : '' }}</span> :: <span class="project">{{ $item->project ? $item->project->naam : ''}}</span></td>
								<td>{{ str_limit($item->opmerkingen, 50, '...') }}</td>
								<td>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['workinghours', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
				                    	<div class="btn-group btn-group-xs">
					                        <a class="btn btn-default btn-xs" href="{{ url('workinghours/' . $item->id . '/edit') }}">
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
			        <div class="pull-right"> {!! $workinghours->appends(Request::except('page'))->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Filter box -->
            <div class="box">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['workinghours'],
                    'id' => 'filter-workinghours'
                ]) !!}
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
				    <div class="form-group">
		                {!! Form::label('period', 'Period: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::text('period', $filter['start']->format( 'd-m-Y') . ' - ' . $filter['end']->format( 'd-m-Y'), ['class' => 'form-control date-range-input', 'readonly' => 'readonly', 'data-selector-start' => '#filter-period-start', 'data-selector-end' => '#filter-period-end'] ) !!}
		                    {!! Form::hidden('start', $filter['start']->format( 'Y-m-d'), ['id' => 'filter-period-start'] ) !!}
		                    {!! Form::hidden('end', $filter['end']->format( 'Y-m-d'), ['id' => 'filter-period-end'] ) !!}
		                </div>
		            </div>
				    <div class="form-group">
		                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::relationProjectSelect('', $relations, array_key_exists( 'relation_project', $filter ) ? $filter['relation_project'] : null, ['class' => 'form-control', 'placeholder' => '']) !!}
		                </div>
		            </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
	                <div class="col-sm-6 col-sm-offset-3">
	                    <button class="btn btn-primary" title="Filter"><i class="fa fa-filter"></i> Filter</button>
	                </div>
                </div>
                {!! Form::close() !!}                	
            </div><!-- /.box -->

            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Workinghours</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('workinghours/create') }}" class="btn btn-primary btn-sm">Add New Workinghour</a><br /><br />
                	
                	A form could be added here, although it is out of scope for scaffolding.
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
