@extends('layouts/adminlte')
 
@section('content') 
{!! Form::model($transaction, [ 
	'method' => 'POST', 
	'url' => ['transaction', $transaction->id, 'store_private'], 
	'class' => 'form-horizontal' 
]) !!}
<div class='row'>
	<div class='col-md-7 col-sm-12'>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Assign transaction as private transfer</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div
					class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
					{!! Form::label('statement.omschrijving', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('Statement[omschrijving]', $description, ['class' => 'form-control']) !!} 
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
