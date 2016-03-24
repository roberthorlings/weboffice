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
				@for($i = 0; $i < $numProjects; $i++)
					{!! Form::hidden('Projects[' . $i . '][id]', $preEnteredProjects[$i]['id']) !!} 
					<div class="row invoice-project">
						<div class="col-sm-4">
							{!! Form::select('Projects[' . $i . '][project_id]', $projects,  $preEnteredProjects[$i]['project_id'], ['class' => 'form-control', 'placeholder' => ' - Select project - ']) !!} 
						</div>
						<div class="col-sm-4">
		                    {!! Form::text('Projects[' . $i . '][period]', $preEnteredProjects[$i]['start']->format( 'd-m-Y') . ' - ' . $preEnteredProjects[$i]['end']->format( 'd-m-Y'), ['class' => 'form-control date-range-input', 'readonly' => 'readonly', 'data-selector-start' => '#project-' . $i . '-start', 'data-selector-end' => '#project-' . $i . '-end'] ) !!}
		                    {!! Form::hidden('Projects[' . $i . '][start]', $preEnteredProjects[$i]['start']->format( 'Y-m-d'), ['id' => 'project-' . $i . '-start'] ) !!}
		                    {!! Form::hidden('Projects[' . $i . '][end]', $preEnteredProjects[$i]['end']->format( 'Y-m-d'), ['id' => 'project-' . $i . '-end'] ) !!}
						</div>
						<div class="col-sm-4">
							{!! Form::select('Projects[' . $i . '][hours_overview_type]', ['default' => 'Default', 'short' => 'Short', 'none' => 'None'],  $preEnteredProjects[$i]['hours_overview_type'], ['class' => 'form-control']) !!} 
						</div>
					</div>
				@endfor
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
			<div class="box-footer row">
				{!! Form::button('Save as new version', ['type' => 'submit', 'name' => 'save-method', 'value' => 'new-version', 'class' => 'btn btn-primary col-sm-6']) !!}
				{!! Form::button('Update current version', ['type' => 'submit', 'name' => 'save-method', 'value' => 'update-current', 'class' => 'btn	btn-default col-sm-6']) !!}
			</div>
			<!-- box-footer -->
			
		</div>    

    {!! Form::close() !!}

@endsection