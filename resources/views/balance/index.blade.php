@extends('layouts/adminlte')
@section('page_title', "Balance")
@section('page_description', $filter['date']->format('d-m-Y'))

@section('js')
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-8'>
            <!-- Box -->
            <div class="box box-primary balance">
                <div class="box-header with-border">
                    <h3 class="box-title">Balance</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
    				<h4>{{ $balance->getDate()->format('d-m-Y') }}</h4>
                	@include('components/balance')
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4'>
            <!-- Box -->
            <div class="box box-primary">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['balance'],
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
		                {!! Form::label('date-input', 'Date: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::text('date-input', $filter['date']->format( 'd-m-Y'), ['class' => 'form-control single-date-input', 'readonly' => 'readonly', 'data-selector-date' => '#filter-single-date'] ) !!}
		                    {!! Form::hidden('date', $filter['date']->format( 'Y-m-d'), ['id' => 'filter-single-date'] ) !!}
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