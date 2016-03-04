@extends('layouts/adminlte')

@section('content')

    {!! Form::model($account, [
        'method' => 'PATCH',
        'url' => ['account', $account->id],
        'class' => 'form-horizontal'
    ]) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Edit Account</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('rekeningnummer') ? 'has-error' : ''}}">
                {!! Form::label('rekeningnummer', 'Rekeningnummer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('rekeningnummer', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('rekeningnummer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('omschrijving', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('bank') ? 'has-error' : ''}}">
                {!! Form::label('bank', 'Bank: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('bank', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('bank', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('post_id') ? 'has-error' : ''}}">
                {!! Form::label('post_id', 'Post: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('post_id', $lists["post_id"], null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('post_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('saldodatum') ? 'has-error' : ''}}">
                {!! Form::label('saldodatum', 'Saldodatum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('saldodatum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('saldodatum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('saldo') ? 'has-error' : ''}}">
                {!! Form::label('saldo', 'Saldo: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('saldo', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('saldo', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

		
		    @if ($errors->any())
		        <ul class="alert alert-danger">
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    @endif
	    
	  </div><!-- /.box-body -->
	  <div class="box-footer">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
	  </div><!-- box-footer -->
	</div><!-- /.box -->

    {!! Form::close() !!}

@endsection
