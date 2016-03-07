@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'statement', 'class' => 'form-horizontal']) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Create new Statement</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('datum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('omschrijving', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('opmerkingen', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            {!! Form::hidden('actief', '1') !!}
            <div class="form-group {{ $errors->has('transactie_id') ? 'has-error' : ''}}">
                {!! Form::label('transactie_id', 'Transactie: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('transactie_id', $lists["transactie_id"], null, ['placeholder' => '', 'class' => 'form-control', ]) !!}
                    {!! $errors->first('transactie_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('activum_id') ? 'has-error' : ''}}">
                {!! Form::label('activum_id', 'Activum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('activum_id', $lists["activum_id"], null, ['placeholder' => '', 'class' => 'form-control', ]) !!}
                    {!! $errors->first('activum_id', '<p class="help-block">:message</p>') !!}
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
            {!! Form::submit('Create', ['class' => 'btn btn-primary form-control']) !!}
	  </div><!-- box-footer -->
	</div><!-- /.box -->

    {!! Form::close() !!}

@endsection