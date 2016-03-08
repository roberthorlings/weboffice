@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/distances.js'))}}
	{{HTML::script(asset('/assets/js/workinghours.form.js'))}}
	{{HTML::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyA4bT94tUoUt-hZGSSDDI8UO1tKUSj6cm8', ['async' => 'async', 'defer' => 'defer'])}}
@endsection
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
	                    {!! Form::text('datum', date('dm'), ['class' => 'form-control', 'required' => 'required']) !!}
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
	            <div class="form-group {{ $errors->has('TravelExpense[wijze]') ? 'has-error' : ''}}">
	                {!! Form::label('TravelExpense[wijze]', 'Wijze: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::select('TravelExpense[wijze]', [ 'auto' => 'Auto', 'fiets' => 'Fiets', 'anders' => 'Anders' ], null, ['class' => 'form-control travel-method', 'required' => 'required']) !!}
	                    {!! $errors->first('TravelExpense[wijze]', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
			    <div class="form-group {{ $errors->has('TravelExpense[van_naar]') ? 'has-error' : ''}}">
	                {!! Form::label('TravelExpense[van_naar]', 'Van/naar: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::text('TravelExpense[van_naar]', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('TravelExpense[van_naar]', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
			    <div class="form-group {{ $errors->has('TravelExpense[bezoekadres]') ? 'has-error' : ''}}">
	                {!! Form::label('TravelExpense[bezoekadres]', 'Bezoekadres: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::textarea('TravelExpense[bezoekadres]', null, ['class' => 'form-control visiting-address', 'data-self-url' => url('relation/self')]) !!}
	                    {!! $errors->first('TravelExpense[bezoekadres]', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>	            
			    <div class="form-group {{ $errors->has('TravelExpense[afstand]') ? 'has-error' : ''}}">
	                {!! Form::label('kilometers', 'Afstand: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::text('kilometers', null, ['class' => 'form-control travel-distance']) !!}
	                    {!! $errors->first('kilometers', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
			    <div class="form-group {{ $errors->has('TravelExpense[km_begin]') ? 'has-error' : ''}}">
	                {!! Form::label('TravelExpense[km_begin]', 'Beginstand km: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::text('TravelExpense[km_begin]', null, ['class' => 'form-control travel-start']) !!}
	                    {!! $errors->first('TravelExpense[km_begin]', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
			    <div class="form-group {{ $errors->has('TravelExpense[km_eind]') ? 'has-error' : ''}}">
	                {!! Form::label('TravelExpense[km_eind]', 'Eindstand km: ', ['class' => 'col-sm-4 control-label']) !!}
	                <div class="col-sm-8">
	                    {!! Form::text('TravelExpense[km_eind]', null, ['class' => 'form-control travel-end']) !!}
	                    {!! $errors->first('TravelExpense[km_eind]', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
		  </div><!-- /.box-body -->

		</div><!-- /.box -->
	  </div>	  
	</div>
    {!! Form::close() !!}

@endsection