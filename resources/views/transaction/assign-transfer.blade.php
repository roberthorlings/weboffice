@extends('layouts/adminlte')
 
@section('content') 
{!! Form::model($transaction, [ 
	'method' => 'POSt', 
	'url' => ['transaction', $transaction->id, 'store_transfer'], 
	'class' => 'form-horizontal' 
]) !!}
<div class='row'>
	<div class='col-md-7 col-sm-12'>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Book transaction as transfer</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="form-group">
					{!! Form::label('saldo_id', 'Opposing statement: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::select('saldo_id', $saldos, $selected_saldo_id, ['class' => 'form-control', 'placeholder' => '- None -']) !!} 
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('account_id', 'Opposing account: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::select('account_id', $accounts, $selected_account_id, ['class' => 'form-control']) !!} 
					</div>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">{!! Form::submit('Update', ['class' => 'btn
				btn-primary form-control']) !!}</div>
			<!-- box-footer -->
			
		</div>
	</div>
	<div class='col-md-5 col-sm-12'>
		@include('/transaction/partials/transaction-details')
		<!-- /.box -->
	</div>
</div>
{!! Form::close() !!} 
@endsection
