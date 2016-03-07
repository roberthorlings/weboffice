@extends('layouts/adminlte')

@section('content')

    {!! Form::model($travelexpense, [
        'method' => 'PATCH',
        'url' => ['travelexpense', $travelexpense->id],
        'class' => 'form-horizontal'
    ]) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Edit Travelexpense</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('van_naar') ? 'has-error' : ''}}">
                {!! Form::label('van_naar', 'Van Naar: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('van_naar', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('van_naar', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('bezoekadres') ? 'has-error' : ''}}">
                {!! Form::label('bezoekadres', 'Bezoekadres: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('bezoekadres', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('bezoekadres', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('km_begin') ? 'has-error' : ''}}">
                {!! Form::label('km_begin', 'Km Begin: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('km_begin', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('km_begin', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('km_eind') ? 'has-error' : ''}}">
                {!! Form::label('km_eind', 'Km End: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('km_eind', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('km_eind', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('afstand') ? 'has-error' : ''}}">
                {!! Form::label('afstand', 'Afstand: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('afstand', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('afstand', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('wijze') ? 'has-error' : ''}}">
                {!! Form::label('wijze', 'Wijze: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('wijze', [ 'auto' => 'Auto', 'fiets' => 'Fiets'],  null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('wijze', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('werktijd_id') ? 'has-error' : ''}}">
                {!! Form::label('werktijd_id', 'Urenregistratie: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('werktijd_id', $lists["werktijd_id"], null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('werktijd_id', '<p class="help-block">:message</p>') !!}
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
