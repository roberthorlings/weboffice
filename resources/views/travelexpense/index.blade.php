@extends('layouts/adminlte')
@section('page_title', "Travel registration")
@section('page_description', toTimespan($filter['start'], $filter['end']))

@section('js')
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
@endsection

@section('content')
    <div class='row travelregistration'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Travel registration</h3>
		            <div class="box-tools pull-right">
			             <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
			             
                        {!! Form::open([
                            'method'=>'POST',
                            'url' => route( 'travelexpense.statement', [ 'start' => $filter['start']->format( 'Y-m-d'), 'end' => $filter['end']->format( 'Y-m-d') ]),
	                        'class' => 'create-statement'
                        ]) !!}
                        {!! Form::close() !!}
			             
			             <ul class="dropdown-menu" role="menu">
			             
		               		<li><a href="{{ route('travelexpense.pdf', [ 'start' => $filter['start']->format( 'Y-m-d'), 'end' => $filter['end']->format( 'Y-m-d') ]) }}">
		               				<i class="fa fa-fw fa-download"></i> Download PDF
		               		</a></li>
                       		<li><a href="#" onClick="$(this).parents('.box-tools').find( 'form.create-statement').submit(); return false;"><i class="fa fa-fw fa-bookmark"></i> Create statement</a></li>
			             </ul>
		            </div>                    
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Date</th><th>Travel</th><th>Distance</th><th>KM</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($travelexpenses as $item)
			                <tr>
			                    <td>{{ $item->WorkingHour->datum->format('d-m-Y') }}
			                    <td>
			                    	{{ $item->van_naar }}<br />
			                    	<span class="visiting-address">{{ $item->bezoekadres }}</span>
			                    </td>
			                    <td>{{ $item->afstand }}</td>
			                    <td class="km">
			                    	@if($item->wijze == 'auto')
			                    		@number($item->km_begin) - @number($item->km_eind)
			                    	@else
			                    		{{ $item->wijze }}
			                    	@endif
			                    </td>
			                    <td>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['travelexpense', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
				                    	<div class="btn-group btn-group-xs">
					                        <a class="btn btn-default btn-xs" href="{{ url('workinghours/' . $item->werktijd_id . '/edit') }}">
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
			        <div class="pull-right"> {!! $travelexpenses->appends(paginationSafeFilter($filter))->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Filter box -->
            <div class="box">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['travelexpense'],
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

            <!-- Statistics box -->
            <div class="box stats">
                <div class="box-header with-border">
                    <h3 class="box-title">Stats</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	@foreach($stats as $stat) 
					    <div class="row">
			                {!! Form::label($stat->wijze, ucfirst($stat->wijze) . ': ', ['class' => 'col-sm-4 control-label']) !!}
			                <div class="col-sm-3 amount">
			                	@number($stat->total)
			                </div>
			            </div>
			        @endforeach
                </div><!-- /.box-body -->
                <div class="box-footer totals">
	                {!! Form::label('totals', 'Total: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-3 amount">
	                    @number($total)
	                </div>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
