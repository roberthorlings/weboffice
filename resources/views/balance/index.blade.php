@extends('layouts/adminlte')
@section('page_title', "Balance")
@section('page_description', $filter['date']->format('d-m-Y'));
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
                	<h4>{{ $filter['date']->format('d-m-Y') }}</h4>
                	<div class="row details">
						@foreach($balance->getBalance() as $side => $posts)
	                		<div class="col-xs-6 side side-{{$side}}">
	                			@foreach( $posts as $total )
				                	<div class="row post-total">
				                		<div class="col-xs-8">
			                				@post($total->getPost())
			                			</div>
			                			<div class="col-xs-4 amount">
			                				@amount($total->getAmount())
			                			</div>
			                		</div>
	                			@endforeach
	                		</div>
	                	@endforeach
                	</div>
                	<div class="row totals">
						@foreach($balance->getTotals() as $side => $total)
	                		<div class="col-xs-6 side side-{{$side}}">
			                	<div class="row">
		                			<div class="col-xs-4 col-xs-offset-8 amount">
		                				@amount($total)
		                			</div>
		                		</div>
	                		</div>
	                	@endforeach
                	</div>
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