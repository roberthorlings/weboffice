@extends('layouts/adminlte')
@section('page_title', "Working hours")
@section('page_description', toTimespan($filter['start'], $filter['end']))

@section('js')
	{{HTML::script(asset('/assets/js/workinghours.index.js'))}}
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
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
			        <table class="table table-bordered table-striped table-hover workinghours">
			            <thead>
			                <tr>
			                    <th>Datum</th><th>Duur</th><th>Klant</th><th>Opmerkingen</th><th>Km</th><th></th>
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
								<td><span class="relatie">{{ $item->Relation ? $item->Relation->bedrijfsnaam : '' }}</span> :: <span class="project">{{ $item->Project ? $item->Project->naam : ''}}</span></td>
								<td>{{ str_limit($item->opmerkingen, 50, '...') }}</td>
			                    <td>
			                    	{{ $item->kilometers > 0 ? $item->kilometers : '' }}
			                    </td>
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
			        <div class="pull-right"> {!! $workinghours->appends(paginationSafeFilter($filter))->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Filter box -->
            <div class="box">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['workinghours'],
                    'id' => 'filter-workinghours',
                    'class' => 'filter'
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
	                <div class="col-sm-8 col-sm-offset-4">
	                    <button class="btn btn-primary" title="Filter"><i class="fa fa-filter"></i> Filter</button>
	                </div>
                </div>
                {!! Form::close() !!}                	
            </div><!-- /.box -->

            <!-- Box -->
            {!! Form::open(['url' => 'workinghours', 'class' => 'form-horizontal', 'id' => 'add-registration']) !!}
            {!! Form::hidden('datum', date('dm')) !!}
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add registration today</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
				    <div class="form-group">
		                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::relationProjectSelect('relation_project', $relationsForEntry, null, ['class' => 'form-control']) !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('begintijd') ? 'has-error' : ''}}">
		                {!! Form::label('begintijd', 'Tijd: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::text('begintijd', null, ['class' => 'form-control', 'required' => 'required']) !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('eindtijd') ? 'has-error' : ''}}">
		                {!! Form::label('eindtijd', 'Eindtijd: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::text('eindtijd', null, ['class' => 'form-control', 'required' => 'required']) !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('pauze') ? 'has-error' : ''}}">
		                {!! Form::label('pauze', 'Pauze: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::number('pauze', null, ['class' => 'form-control']) !!}
		                </div>
		            </div>
		
		            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
		                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
		                </div>
		            </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<div class="col-sm-8 col-sm-offset-4">
		            	{!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
	                	<a href="{{ url('workinghours/create') }}" class="add-more-info btn btn-default">More info</a><br /><br />
	                </div>
                </div>
            </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
