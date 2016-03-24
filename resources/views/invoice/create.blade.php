@extends('layouts/adminlte')

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
		            
				    <div class="form-group">
		                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::relationProjectSelect('relation_project', $relations, $relation_project, ['class' => 'form-control', 'placeholder' => '']) !!}
		                </div>
		            </div>

		            <div class="form-group {{ $errors->has('uurtje_factuurtje') ? 'has-error' : ''}}">
		                {!! Form::label('uurtje_factuurtje', 'Uurtje Factuurtje: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
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
	            <div class="form-group {{ $errors->has('creditfactuur') ? 'has-error' : ''}}">
	                {!! Form::label('creditfactuur', 'Creditfactuur: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-9">
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
	                <div class="col-sm-9">
	                    {!! Form::text('oorspronkelijk_factuurnummer', null, ['class' => 'form-control']) !!}
	                    {!! $errors->first('oorspronkelijk_factuurnummer', '<p class="help-block">:message</p>') !!}
	                </div>
	            </div>
	            <div class="form-group {{ $errors->has('oorspronkelijk_datum') ? 'has-error' : ''}}">
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

    
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Invoice lines</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@for($i = 0; $i < $numLines; $i++)
					{!! Form::hidden('Lines[' . $i . '][id]', $preEnteredLines[$i]['id']) !!} 
					<div class="row transaction-line">
						<div class="col-sm-4">
							{!! Form::text('Lines[' . $i . '][omschrijving]',  $preEnteredLines[$i]['omschrijving'], ['class' => 'form-control']) !!} 
						</div>
						<div class="col-sm-3">
							{!! Form::text('Lines[' . $i . '][extra]',  $preEnteredLines[$i]['extra'], ['class' => 'form-control']) !!} 
						</div>
						<div class="col-sm-1">
							{!! Form::input('number', 'Lines[' . $i . '][aantal]', $preEnteredLines[$i]['aantal'], ['class' => 'form-control statement-amount', 'step' => 'any']) !!} 
						</div>
						<div class="col-sm-1">
							{!! Form::input('number', 'Lines[' . $i . '][prijs]', $preEnteredLines[$i]['prijs'], ['class' => 'form-control statement-amount', 'step' => 'any']) !!} 
						</div>
						<div class="col-sm-3">
							{!! Form::postSelect('Lines[' . $i . '][post_id]', $posts, $preEnteredLines[$i]['post_id'], ['class' => 'form-control', 'placeholder' => ' - No post selected - ']) !!} 
						</div>
					</div>
				@endfor
				
				<div class="row total">
					<div class="col-sm-1 col-sm-offset-8">
						{!! Form::input('number', 'totaalbedrag', null, ['class' => 'form-control statement-total-amount', 'readonly' => 'readonly']) !!} 
					</div>
				</div>
								
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				{!! Form::submit('Create', ['class' => 'btn	btn-primary form-control']) !!}
			</div>
			<!-- box-footer -->
			
		</div>    

    {!! Form::close() !!}

@endsection