@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/invoices.form.js'))}}
@endsection
@section('content')

    {!! Form::open(['url' => 'invoice', 'class' => 'form-horizontal']) !!}

	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Invoice metadata</h3>
			  </div><!-- /.box-header -->
				  <div class="box-body">
				  
		            <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
		                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::date('datum', $date, ['class' => 'form-control']) !!}
		                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
				    <div class="form-group {{ $errors->has('factuurnummer') ? 'has-error' : ''}}">
		                {!! Form::label('factuurnummer', 'Factuurnummer: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::text('factuurnummer', $number, ['class' => 'form-control']) !!}
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
		            
				    <div class="form-group">
		                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::relationProjectSelect('relation_project', $relations, $relation_project, ['class' => 'form-control', 'placeholder' => '']) !!}
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
			<div class="box">
			  <div class="box-header with-border">
			    <h3 class="box-title">Creditnote</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
	            <div class="form-group creditnote-radio {{ $errors->has('creditfactuur') ? 'has-error' : ''}}">
	                {!! Form::label('creditfactuur', 'Creditfactuur: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-9">
	                                <div class="checkbox">
	                <label>{!! Form::radio('creditfactuur', '1', $creditnote) !!} Yes</label>
	            </div>
	            <div class="checkbox">
	                <label>{!! Form::radio('creditfactuur', '0', !$creditnote) !!} No</label>
	            </div>
	                    {!! $errors->first('creditfactuur', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('oorspronkelijk_factuurnummer') ? 'has-error' : ''}} creditnote-fields" @if(!$creditnote)style="display: none;"@endif>
	                {!! Form::label('oorspronkelijk_factuurnummer', 'Oorspronkelijk Factuurnummer: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-9">
	                    {!! Form::text('oorspronkelijk_factuurnummer', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('oorspronkelijk_factuurnummer', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('oorspronkelijk_datum') ? 'has-error' : ''}} creditnote-fields" @if(!$creditnote)style="display: none;"@endif>
	                {!! Form::label('oorspronkelijk_datum', 'Oorspronkelijk Datum: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-9">
	                    {!! Form::date('oorspronkelijk_datum', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('oorspronkelijk_datum', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
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
			<div class="box-footer">
				{!! Form::submit('Create', ['class' => 'btn	btn-primary form-control']) !!}
			</div>
			<!-- box-footer -->
			
		</div>    

    {!! Form::close() !!}

@endsection