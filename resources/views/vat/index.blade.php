@extends('layouts/adminlte')
@section('page_title', "VAT statement")
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
                    <h3 class="box-title">Statement lines regarding VAT</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table ledgers">
			            <tbody>
			            @foreach($vatstatement->getLedgers() as $idx => $ledger)
			            	@include('ledger/partials/ledger-row')
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Proposed statements for VAT declaration</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table statements">
			            <tbody>
			            	@include('statement/partials/statement-row', ['idx' => 0, 'statement' => $vatstatement->getDeclarationStatement()])
			            	@include('statement/partials/statement-row', ['idx' => 0, 'statement' => $vatstatement->getPaymentStatement()])
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <div class="box">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['vat'],
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
        
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Statement</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                {!! Form::open([
                    'method'=>'POST',
                    'url' => route('vat.book'),
                ]) !!}
                    {!! Form::hidden('start', $filter['start']->format( 'Y-m-d') ) !!}
                    {!! Form::hidden('end', $filter['end']->format( 'Y-m-d') ) !!}
                    <button class="btn btn-primary" title="Filter" type="submit"><i class="fa fa-bookmark"></i> Create booking</button>
                {!! Form::close() !!}                	
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
