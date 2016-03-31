@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/relations.form.js'))}}
@endsection
@section('page_title', "Edit relation")

@section('content')

    {!! Form::model($relation, [
        'method' => 'PATCH',
        'url' => ['relation', $relation->id],
        'class' => 'form-horizontal'
    ]) !!}
    
    <div class="row">
    	<div class="col-sm-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Relation</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
				    <div class="form-group {{ $errors->has('bedrijfsnaam') ? 'has-error' : ''}}">
		                {!! Form::label('bedrijfsnaam', 'Bedrijfsnaam: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('bedrijfsnaam', null, ['class' => 'form-control address-field', 'required' => 'required']) !!}
		                    {!! $errors->first('bedrijfsnaam', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('contactpersoon') ? 'has-error' : ''}}">
		                {!! Form::label('contactpersoon', 'Contactpersoon: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('contactpersoon', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('contactpersoon', '<p class="help-block">:message</p>') !!}
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
    	<div class="col-sm-6">
    		<div class="box box-default">
			  <div class="box-header with-border">
			    <h3 class="box-title">Contact information</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            
		            <div class="form-group {{ $errors->has('telefoon') ? 'has-error' : ''}}">
		                {!! Form::label('telefoon', 'Telefoon: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('telefoon', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('telefoon', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('mobiel') ? 'has-error' : ''}}">
		                {!! Form::label('mobiel', 'Mobiel: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('mobiel', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('mobiel', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('fax') ? 'has-error' : ''}}">
		                {!! Form::label('fax', 'Fax: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('fax', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('fax', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
		                {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
		                {!! Form::label('website', 'Website: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('website', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
			    
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
    	
    	</div>
    </div>

    <div class="row">
    	<div class="col-sm-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Address</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            <div class="form-group {{ $errors->has('adres') ? 'has-error' : ''}}">
		                {!! Form::label('adres', 'Adres: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('adres', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('adres', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('postcode') ? 'has-error' : ''}}">
		                {!! Form::label('postcode', 'Postcode: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('postcode', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('postcode', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('plaats') ? 'has-error' : ''}}">
		                {!! Form::label('plaats', 'Plaats: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('plaats', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('plaats', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('land') ? 'has-error' : ''}}">
		                {!! Form::label('land', 'Land: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('land', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('land', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
    	
    	</div>
    	<div class="col-sm-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Postal address</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            <div class="form-group {{ $errors->has('postadres') ? 'has-error' : ''}}">
		                {!! Form::label('postadres', 'Adres: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('postadres', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('postadres', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('postpostcode') ? 'has-error' : ''}}">
		                {!! Form::label('postpostcode', 'Postcode: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('postpostcode', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('postpostcode', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('postplaats') ? 'has-error' : ''}}">
		                {!! Form::label('postplaats', 'Plaats: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('postplaats', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('postplaats', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('postland') ? 'has-error' : ''}}">
		                {!! Form::label('postland', 'Land: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::text('postland', null, ['class' => 'form-control address-field']) !!}
		                    {!! $errors->first('postland', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
			    
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
    	
    	</div>
    </div>
    
    <div class="row">
       	<div class="col-sm-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Invoice address</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
   		            <div class="form-group {{ $errors->has('factuuradres') ? 'has-error' : ''}}">
		                {!! Form::label('', 'Changed invoice address: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6 checkbox">
		                	<label>
		                    	{!! Form::checkbox('changed-invoice-address', 1, null, ['class' => 'changed-invoice-address']) !!}
		                    </label>
		                </div>
		            </div>
   		            <div class="invoice-address form-group {{ $errors->has('factuuradres') ? 'has-error' : ''}}">
		                {!! Form::label('factuuradres', 'Factuuradres: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::textarea('factuuradres', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('factuuradres', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
    	
    	</div>
    
       	<div class="col-sm-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Metadata</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
		                {!! Form::label('type', 'Type: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::select('type', [ 0 => 'Active customer', 1 => 'Inactive customer', 2 => 'Supplier', 3 => 'Other'], null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('werktijd') ? 'has-error' : ''}}">
		                {!! Form::label('werktijd', 'Register hours: ', ['class' => 'col-sm-3 control-label']) !!}
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
		            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
		                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('opmerkingen', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
    	
    	</div>
    
    </div>

    {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}

    {!! Form::close() !!}

@endsection
