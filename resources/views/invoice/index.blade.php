@extends('layouts/adminlte')
@section('page_title', "Invoices")
@section('page_description', toTimespan($filter['start'], $filter['end']))

@section('js')
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Invoices</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover documents invoices">
			            <thead>
			                <tr>
			                    <th>Date</th><th>Factuurnummer</th><th>Versie</th><th>Relation</th><th>Titel</th><th>Amount</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($invoices as $item)
			                <tr class="
			                	{{ $item->definitief ? 'final' : 'concept' }}
			                ">
			                    <td>{{ $item->datum->format('d-m-Y')}}</td>
			                    <td>{{ $item->factuurnummer }}</td>
			                    <td>{{ $item->versie }}</td>
			                    <td>{{ $item->Relation->bedrijfsnaam }}</td>
			                    <td><a href="{{ url('invoice', $item->id) }}" class="title">{{ $item->titel }}</a></td>
			                    <td class="amount">@amount($item->totaalbedrag)</td>
			                    <td align="right">
			                        @if(!$item->definitief)
				                        {!! Form::open([
				                            'method'=>'POST',
				                            'url' => ['invoice', $item->id, 'mark_as_final'],
				                            'style' => 'display:inline',
				                        ]) !!}
					                        {!! Form::button('<i class="fa fa-fw fa-anchor"></i>', ['class' => 'btn btn-default btn-xs', 'type' => 'submit']) !!}
			                        	{!! Form::close() !!}
						            @endif
			                    
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['invoice', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
				                    	<div class="btn-group btn-group-xs">
					                        <a class="btn btn-default btn-xs" href="{{ url('invoice/' . $item->id . '/edit') }}">
					                            <i class="fa fa-fw fa-pencil"></i>
					                        </a>
				                            {!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['class' => 'btn btn-danger btn-xs', 'type' => 'submit']) !!}
					                    </div>
			                        {!! Form::close() !!}
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $invoices->appends(paginationSafeFilter($filter))->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <div class="box">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['invoice'],
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
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
