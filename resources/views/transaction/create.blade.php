@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'transaction', 'class' => 'form-horizontal']) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Create new Transaction</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('omschrijving', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('bedrag') ? 'has-error' : ''}}">
                {!! Form::label('bedrag', 'Bedrag: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('bedrag', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('bedrag', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('datum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('tegenrekening') ? 'has-error' : ''}}">
                {!! Form::label('tegenrekening', 'Tegenrekening: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('tegenrekening', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('tegenrekening', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('ingedeeld') ? 'has-error' : ''}}">
                {!! Form::label('ingedeeld', 'Ingedeeld: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('ingedeeld', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('ingedeeld', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('ingedeeld', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('rekening_id') ? 'has-error' : ''}}">
                {!! Form::label('rekening_id', 'Rekening: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('rekening_id', $lists["rekening_id"], null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('rekening_id', '<p class="help-block">:message</p>') !!}
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