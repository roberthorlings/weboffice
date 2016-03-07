@extends('layouts/adminlte')

@section('content')

    {!! Form::model($invoice, [
        'method' => 'PATCH',
        'url' => ['invoice', $invoice->id],
        'class' => 'form-horizontal'
    ]) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Edit Invoice</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('factuurnummer') ? 'has-error' : ''}}">
                {!! Form::label('factuurnummer', 'Factuurnummer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('factuurnummer', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('factuurnummer', '<p class="help-block">:message</p>') !!}
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
            <div class="form-group {{ $errors->has('referentie') ? 'has-error' : ''}}">
                {!! Form::label('referentie', 'Referentie: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('referentie', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('referentie', '<p class="help-block">:message</p>') !!}
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
            <div class="form-group {{ $errors->has('uurtje_factuurtje') ? 'has-error' : ''}}">
                {!! Form::label('uurtje_factuurtje', 'Uurtje Factuurtje: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('uurtje_factuurtje', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('uurtje_factuurtje', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('uurtje_factuurtje', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('btw') ? 'has-error' : ''}}">
                {!! Form::label('btw', 'Btw: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('btw', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('btw', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('btw', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('creditfactuur') ? 'has-error' : ''}}">
                {!! Form::label('creditfactuur', 'Creditfactuur: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('creditfactuur', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('creditfactuur', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('creditfactuur', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('oorspronkelijk_factuurnummer') ? 'has-error' : ''}}">
                {!! Form::label('oorspronkelijk_factuurnummer', 'Oorspronkelijk Factuurnummer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('oorspronkelijk_factuurnummer', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('oorspronkelijk_factuurnummer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('oorspronkelijk_datum') ? 'has-error' : ''}}">
                {!! Form::label('oorspronkelijk_datum', 'Oorspronkelijk Datum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('oorspronkelijk_datum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('oorspronkelijk_datum', '<p class="help-block">:message</p>') !!}
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
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
	  </div><!-- box-footer -->
	</div><!-- /.box -->

    {!! Form::close() !!}

@endsection
