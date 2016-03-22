@extends('layouts/adminlte')

@section('content')
    {!! Form::model($asset, [
        'method' => 'post',
        'url' => ['asset', $asset->id, 'statements'],
        'class' => 'form-horizontal'
    ]) !!}
    <div class="row">
    	<div class="col-sm-6 col-xs-12">
			<div class="box box-primary asset-booking">
			  <div class="box-header with-border">
			    <h3 class="box-title">Statement for investment</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            <div class="form-group">
		                {!! Form::label('book_investment', 'Book investment: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                	@if($asset->isInvestmentBooked()) 		            
			                    Investment has been booked already
		                    @else
			                    {!! Form::checkbox('book_investment', '1', true) !!}
		                    @endif
		                </div>
		            </div>
		            
					@if(!$asset->isInvestmentBooked()) 		            
					    <div class="form-group">
			                {!! Form::label('invoicenumber', 'Invoice: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::text('invoicenumber', null, ['class' => 'form-control']) !!}
			                </div>
			            </div>
			            <div class="form-group">
			                {!! Form::label('seller', 'Seller: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::select('seller', $relations, null, ['class' => 'form-control']) !!}
			                </div>
			            </div>
			            
				        <table class="table table-bordered statements">
				            <tbody>
				            	@include('statement/partials/statement-row', [ 'idx' => 0, 'statement' => $investmentStatement ])
				            </tbody>
				        </table>
				    @endif
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
		<div class="col-sm-6 col-xs-12">
			<div class="box box-primary asset-booking">
			  <div class="box-header with-border">
			    <h3 class="box-title">Statement for amortization</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            <div class="form-group">
		                {!! Form::label('book_amortization', 'Book amortization: ', ['class' => 'col-sm-3 control-label' ]) !!}
		                <div class="col-sm-6">
					    	@if($asset->amortization()->isFinished())
				                <div class="col-sm-6 col-sm-offset-3">
							    	Amortization is finished.  
							    </div>
							@else		                
		                    	{!! Form::checkbox('book_amortization', '1', true) !!}
		                    @endif
		                </div>
		            </div>
		            @if(!$asset->amortization()->isFinished())
			            <div class="form-group asset-calculation">
			                {!! Form::label('amount', 'Amount: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-8">
			                	<div class="row">
				                	<div class="col-xs-8">Aanschafwaarde</div>
				                	<div class="col-xs-4 amount">@amount($asset->bedrag)</div>
								</div>
			                	<div class="row">
				                	<div class="col-xs-8">Restwaarde</div>
				                	<div class="col-xs-4 amount">@amount(-$asset->restwaarde)</div>
								</div>
			                	<div class="row">
				                	<div class="col-xs-8">Al afgeschreven</div>
				                	<div class="col-xs-4 amount">@amount(-$asset->amortization()->getAmountAlreadyAmortized())</div>
								</div>
			                	<div class="row calculation-result">
				                	<div class="col-xs-8">Nog af te schrijven</div>
				                	<div class="col-xs-4 amount">@amount($asset->amortization()->getAmountStillToAmortize())</div>
								</div>
			                	<div class="row">
				                	<div class="col-xs-8">Aantal periodes</div>
				                	<div class="col-xs-4 amount">/ {{ $asset->amortization()->getPeriodsToAmortize() }}</div>
								</div>
			                	<div class="row calculation-result">
				                	<div class="col-xs-8">Amortization per period</div>
				                	<div class="col-xs-4 amount">@amount($asset->amortization()->getAmountStillToAmortize() / $asset->amortization()->getPeriodsToAmortize())</div>
								</div>
			                </div>
			            </div>
			            
				        <table class="table table-bordered statements">
				            <tbody>
				            	@include('statement/partials/statement-row', [ 'idx' => 0, 'statement' => $amortizationStatement ])
				            </tbody>
				        </table>
				    @endif
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
    {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
    {!! Form::close() !!}

@endsection
