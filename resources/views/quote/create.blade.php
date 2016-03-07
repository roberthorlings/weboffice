@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'quote', 'class' => 'form-horizontal']) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Create new Quote</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('offertenummer') ? 'has-error' : ''}}">
                {!! Form::label('offertenummer', 'Offertenummer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('offertenummer', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('offertenummer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('versie') ? 'has-error' : ''}}">
                {!! Form::label('versie', 'Versie: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('versie', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('versie', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('titel') ? 'has-error' : ''}}">
                {!! Form::label('titel', 'Titel: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('titel', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('titel', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('totaalbedrag') ? 'has-error' : ''}}">
                {!! Form::label('totaalbedrag', 'Totaalbedrag: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('totaalbedrag', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('totaalbedrag', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('datum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('vervaldatum') ? 'has-error' : ''}}">
                {!! Form::label('vervaldatum', 'Vervaldatum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('vervaldatum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('vervaldatum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('definitief') ? 'has-error' : ''}}">
                {!! Form::label('definitief', 'Definitief: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('definitief', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('definitief', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('definitief', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('relatie_id') ? 'has-error' : ''}}">
                {!! Form::label('relatie_id', 'Relatie: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('relatie_id', $lists["relatie_id"], null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('relatie_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('project_id') ? 'has-error' : ''}}">
                {!! Form::label('project_id', 'Project: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('project_id', $lists["project_id"], null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('project_id', '<p class="help-block">:message</p>') !!}
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