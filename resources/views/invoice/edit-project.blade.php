@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/invoices.form.js'))}}
@endsection
@section('content')

    {!! Form::model($invoice, [
        'method' => 'PATCH',
        'url' => ['invoice', $invoice->id],
        'class' => 'form-horizontal'
    ]) !!}
    {!! Form::hidden('uurtje_factuurtje', true) !!}
    
		<div class="row">
		<div class="col-sm-6 col-xs-12">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Project invoice metadata</h3>
			  </div><!-- /.box-header -->
				  <div class="box-body">
		            <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
		                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::date('datum', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
				    <div class="form-group {{ $errors->has('factuurnummer') ? 'has-error' : ''}}">
		                {!! Form::label('factuurnummer', 'Factuurnummer: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::text('factuurnummer', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('factuurnummer', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            
		            <div class="form-group {{ $errors->has('titel') ? 'has-error' : ''}}">
		                {!! Form::label('titel', 'Titel: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::text('titel', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('titel', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('referentie') ? 'has-error' : ''}}">
		                {!! Form::label('referentie', 'Referentie: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::text('referentie', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('referentie', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('relatie_id') ? 'has-error' : ''}}">
		                {!! Form::label('relatie_id', 'Klant: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::select('relatie_id', $relations->lists('bedrijfsnaam', 'id'), null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('relatie_id', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>		         
		            <div class="form-group {{ $errors->has('btw') ? 'has-error' : ''}}">
		                {!! Form::label('btw', 'Btw: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
                            <div class="checkbox">
				                <label>{!! Form::radio('btw', '1') !!} Yes</label>
				            </div>
				            <div class="checkbox">
				                <label>{!! Form::radio('btw', '0', true) !!} No</label>
				            </div>
		                    {!! $errors->first('btw', '<p class="help-block">:message</p>') !!}
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
		
		<div class="col-sm-6 col-xs-12">
			<div class="box projects">
			  <div class="box-header with-border">
			    <h3 class="box-title">Projects</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
				@include('invoice/partials/project-lines')
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
    
		<div class="box box-primary invoice-details">
			<div class="box-header with-border">
				<h3 class="box-title">Invoice lines</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@include('invoice/partials/invoice-lines')
			</div>
			<!-- /.box-body -->
			<div class="box-footer row">
				{!! Form::button('Save as new version', ['type' => 'submit', 'name' => 'save-method', 'value' => 'new-version', 'class' => 'btn btn-primary col-sm-6']) !!}
				{!! Form::button('Update current version', ['type' => 'submit', 'name' => 'save-method', 'value' => 'update-current', 'class' => 'btn	btn-default col-sm-6']) !!}
			</div>
			<!-- box-footer -->
			
		</div>    

    {!! Form::close() !!}

@endsection