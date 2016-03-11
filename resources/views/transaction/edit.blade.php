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
	<div class='col-md-8 col-sm-12'>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Edit Transaction statement</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div
					class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
					{!! Form::label('statement.omschrijving', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-6">
						{!! Form::text('Statement[omschrijving]', $statement ? $statement->omschrijving : null, ['class' => 'form-control']) !!} 
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statement.lines', 'Booked at: ', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-6">
						@for($i = 0; $i < $numLines; $i++)
							{!! Form::hidden('Statement[lines][' . $i . '][id]', $preEnteredLines[$i]['id']) !!} 
							<div class="row">
								<div class="col-sm-4">
									{!! Form::input('number', 'Statement[lines][' . $i . '][amount]', $preEnteredLines[$i]['amount'], ['class' => 'form-control statement-amount', 'step' => 'any']) !!} 
								</div>
								<div class="col-sm-8">
									{!! Form::select('Statement[lines][' . $i . '][post_id]', $posts, $preEnteredLines[$i]['post_id'], ['class' => 'form-control']) !!} 
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
	<div class='col-md-4 col-sm-12'>
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Transaction details</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div
					class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
					{!! Form::label('datum', 'Date: ', ['class' => 'col-sm-4 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('datum', $transaction->datum->format('d-m-Y'), ['class' => 'form-control', 'disabled' => 'disabled']) !!} 
					</div>
				</div>
				<div
					class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
					{!! Form::label('omschrijving', 'Original: ', ['class' => 'col-sm-4 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('omschrijving', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!} 
					</div>
				</div>
				<div
					class="form-group {{ $errors->has('bedrag') ? 'has-error' : ''}}">
					{!! Form::label('bedrag', 'Amount: ', ['class' => 'col-sm-4	control-label']) !!}
					<div class="col-sm-8">
						{!! Form::number('bedrag', number_format($transaction->bedrag, 2, '.', ''), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
					</div>
				</div>
				<div
					class="form-group">
					{!! Form::label('account', 'Account: ', ['class' => 'col-sm-4 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('account', $transaction->Account->description, ['class' => 'form-control', 'disabled' => 'disabled']) !!} 
					</div>
				</div>
				<div
					class="form-group {{ $errors->has('tegenrekening') ? 'has-error' : ''}}">
					{!! Form::label('tegenrekening', 'Opposing account: ', ['class' => 'col-sm-4 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('tegenrekening', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!} 
					</div>
				</div>
			</div>
		</div>
		<!-- /.box -->
	</div>
</div>
{!! Form::close() !!} 
@endsection
