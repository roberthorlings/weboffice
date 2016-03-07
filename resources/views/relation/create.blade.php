@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'relation', 'class' => 'form-horizontal']) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Create new Relation</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('bedrijfsnaam') ? 'has-error' : ''}}">
                {!! Form::label('bedrijfsnaam', 'Bedrijfsnaam: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('bedrijfsnaam', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('bedrijfsnaam', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('contactpersoon') ? 'has-error' : ''}}">
                {!! Form::label('contactpersoon', 'Contactpersoon: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('contactpersoon', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('contactpersoon', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('adres') ? 'has-error' : ''}}">
                {!! Form::label('adres', 'Adres: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('adres', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('adres', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postcode') ? 'has-error' : ''}}">
                {!! Form::label('postcode', 'Postcode: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('postcode', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('postcode', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('plaats') ? 'has-error' : ''}}">
                {!! Form::label('plaats', 'Plaats: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('plaats', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('plaats', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('land') ? 'has-error' : ''}}">
                {!! Form::label('land', 'Land: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('land', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('land', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('telefoon') ? 'has-error' : ''}}">
                {!! Form::label('telefoon', 'Telefoon: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('telefoon', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('telefoon', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('fax') ? 'has-error' : ''}}">
                {!! Form::label('fax', 'Fax: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('fax', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('fax', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('mobiel') ? 'has-error' : ''}}">
                {!! Form::label('mobiel', 'Mobiel: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('mobiel', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('mobiel', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
                {!! Form::label('website', 'Website: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('website', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('opmerkingen', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('project_count') ? 'has-error' : ''}}">
                {!! Form::label('project_count', 'Project Count: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('project_count', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('project_count', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postadres') ? 'has-error' : ''}}">
                {!! Form::label('postadres', 'Postadres: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('postadres', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('postadres', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postpostcode') ? 'has-error' : ''}}">
                {!! Form::label('postpostcode', 'Postpostcode: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('postpostcode', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('postpostcode', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postplaats') ? 'has-error' : ''}}">
                {!! Form::label('postplaats', 'Postplaats: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('postplaats', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('postplaats', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postland') ? 'has-error' : ''}}">
                {!! Form::label('postland', 'Postland: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('postland', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('postland', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                {!! Form::label('type', 'Type: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('type', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('werktijd') ? 'has-error' : ''}}">
                {!! Form::label('werktijd', 'Werktijd: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('werktijd', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('werktijd', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('werktijd', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('factuuradres') ? 'has-error' : ''}}">
                {!! Form::label('factuuradres', 'Factuuradres: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('factuuradres', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('factuuradres', '<p class="help-block">:message</p>') !!}
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