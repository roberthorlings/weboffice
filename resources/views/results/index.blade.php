@extends('layouts/adminlte')

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