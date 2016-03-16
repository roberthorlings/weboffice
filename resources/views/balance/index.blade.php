@extends('layouts/adminlte')

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
                	<h4>{{ $date->format('d-m-Y') }}</h4>
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
                <div class="box-header with-border">
                    <h3 class="box-title">Second Box</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    A separate section to add any kind of widget. Feel free
                    to explore all of AdminLTE widgets by visiting the demo page
                    on <a href="https://almsaeedstudio.com">Almsaeed Studio</a>.
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection