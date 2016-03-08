@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'workinghours', 'class' => 'form-horizontal']) !!}
    <div class="row">
	  <div class=" col-md-8 col-sm-12">
		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title">Urenregistratie</h3>
		  </div><!-- /.box-header -->
		  <div class="box-body">
			    <div class="form-group">
	                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::relationProjectSelect('relation_project', $relations, null, ['class' => 'form-control']) !!}
	                </div>
	            </div>
	
			    <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
	                {!! Form::label('datum', 'Datum (ddmmyy): ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::text('datum', null, ['class' => 'form-control', 'required' => 'required']) !!}
	                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('begintijd') ? 'has-error' : ''}}">
	                {!! Form::label('begintijd', 'Tijd: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::text('begintijd', null, ['class' => 'form-control', 'required' => 'required']) !!}
	                    {!! $errors->first('begintijd', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('eindtijd') ? 'has-error' : ''}}">
	                {!! Form::label('eindtijd', 'Eindtijd: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::text('eindtijd', null, ['class' => 'form-control', 'required' => 'required']) !!}
	                    {!! $errors->first('eindtijd', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('pauze') ? 'has-error' : ''}}">
	                {!! Form::label('pauze', 'Pauze: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::number('pauze', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('pauze', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	
	            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
	                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('opmerkingen', '<p class="help-block">:message</p>') !!}
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
	  </div>
	  
	  <div class=" col-md-4 col-sm-12">
		<div class="box">
		  <div class="box-header with-border">
		    <h3 class="box-title">Reiskosten</h3>
		  </div><!-- /.box-header -->
		  <div class="box-body">
			    <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
	                {!! Form::label('datum', 'Datum (ddmmyy): ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::text('datum', null, ['class' => 'form-control', 'required' => 'required']) !!}
	                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('begintijd') ? 'has-error' : ''}}">
	                {!! Form::label('begintijd', 'Tijd: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::text('begintijd', null, ['class' => 'form-control', 'required' => 'required']) !!}
	                    {!! $errors->first('begintijd', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('eindtijd') ? 'has-error' : ''}}">
	                {!! Form::label('eindtijd', 'Eindtijd: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::text('eindtijd', null, ['class' => 'form-control', 'required' => 'required']) !!}
	                    {!! $errors->first('eindtijd', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('pauze') ? 'has-error' : ''}}">
	                {!! Form::label('pauze', 'Pauze: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::number('pauze', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('pauze', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	
	            <div class="form-group {{ $errors->has('kilometers') ? 'has-error' : ''}}">
	                {!! Form::label('kilometers', 'Kilometers: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::number('kilometers', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('kilometers', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
	                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('opmerkingen', '<p class="help-block">:message</p>') !!}
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

		</div><!-- /.box -->
	  </div>	  
	</div>
    {!! Form::close() !!}

@endsection