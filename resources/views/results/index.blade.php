@extends('layouts/adminlte')
@section('page_title', "Financial results")
@section('page_description', toTimespan($filter['start'], $filter['end']));
@section('js')
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
@endsection
@section('content')
    <div class='row'>
        <div class='col-md-8'>
            <!-- Box -->
            <div class="box 
				@if($statement->getResultTotal() < 0)
					box-success
				@else
					box-danger
				@endif 
				results">
            	@include('results/partials/result-overview', ['collapsed' => false, 'title' => 'Results', 'statementPart' => $statement->getResults(), 'totalTitle' => 'Results', 'totalAmount' => $statement->getResultTotal() ])
            </div><!-- /.box -->
            
			@if(abs($statement->getOtherTotal()) > 0.005)
	            <!-- Box -->
	            <div class="box results collapsed-box">
	            	@include('results/partials/result-overview', ['collapsed' => true, 'title' => 'Other equity changes', 'statementPart' => $statement->getOther(), 'totalTitle' => 'Total', 'totalAmount' => $statement->getOtherTotal() ])
	            </div><!-- /.box -->
	            
	            <!-- Box -->
	            <div class="box box-primary results collapsed-box">
	            	@include('results/partials/result-overview', ['collapsed' => true, 'title' => 'Change in equity', 'statementPart' => [], 'totalTitle' => 'Change in equity', 'totalAmount' => $statement->getEquityChangesTotal() ])
	            </div><!-- /.box -->
	            
	        @endif
            
			@if(abs($statement->getLimitedTotal()) > 0.005)
	            <!-- Box -->
	            <div class="box results collapsed-box">
	            	@include('results/partials/result-overview', ['collapsed' => true, 'title' => 'Partially deductable', 'statementPart' => $statement->getLimited(), 'totalTitle' => 'Total', 'totalAmount' => $statement->getLimitedTotal() ])
	            </div><!-- /.box -->
	        @endif
	        
            
        </div><!-- /.col -->
        
        <div class='col-md-4'>
            <!-- Box -->
            <div class="box box-default">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['results'],
                    'id' => 'filter-results',
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