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