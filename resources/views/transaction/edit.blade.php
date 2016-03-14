@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/transactions.form.js'))}}
@endsection
 
@section('content') 
{!! Form::model($transaction, [ 
	'method' => 'PATCH', 
	'url' => ['transaction', $transaction->id], 
	'class' => 'form-horizontal' 
]) !!}
<div class='row'>
	<div class='col-md-7 col-sm-12'>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Edit Transaction statement</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div
					class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
					{!! Form::label('statement.omschrijving', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('Statement[omschrijving]', $statement ? $statement->omschrijving : null, ['class' => 'form-control']) !!} 
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statement.lines', 'Booked at: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-8">
						@for($i = 0; $i < $numLines; $i++)
							{!! Form::hidden('Statement[lines][' . $i . '][id]', $preEnteredLines[$i]['id']) !!} 
							<div class="row">
								<div class="col-sm-4">
									{!! Form::input('number', 'Statement[lines][' . $i . '][amount]', $preEnteredLines[$i]['amount'], ['class' => 'form-control statement-amount', 'step' => 'any']) !!} 
								</div>
								<div class="col-sm-8">
									{!! Form::postSelect('Statement[lines][' . $i . '][post_id]', null, $preEnteredLines[$i]['post_id'], ['class' => 'form-control', 'placeholder' => ' - No post selected - ']) !!} 
								</div>
							</div>
						@endfor
						
						<div class="row total">
							<div class="col-sm-4">
								{!! Form::input('number', 'total', number_format($sum, 2, '.', ''), ['class' => 'form-control statement-total-amount', 'readonly' => 'readonly']) !!} 
							</div>
						</div>
						
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
