@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/statements.form.js'))}}
@endsection
 
@section('content')

    {!! Form::model($statement, [
        'method' => 'PATCH',
        'url' => ['statement', $statement->id],
        'class' => 'form-horizontal'
    ]) !!}
		<div class='row'>
			<div class='col-md-6 col-sm-12'>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Statement lines</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						@for($i = 0; $i < $numLines; $i++)
							{!! Form::hidden('Lines[' . $i . '][id]', $preEnteredLines[$i]['id']) !!} 
							<div class="row transaction-line">
								<div class="col-sm-2">
									{!! Form::select('Lines[' . $i . '][credit]', [ '0' => '', '1' => 'Aan' ], $preEnteredLines[$i]['credit'], ['class' => 'form-control statement-sign']) !!} 
								</div>
								<div class="col-sm-7">
									{!! Form::postSelect('Lines[' . $i . '][post_id]', $posts, $preEnteredLines[$i]['post_id'], ['class' => 'form-control', 'placeholder' => ' - No post selected - ']) !!} 
								</div>
								<div class="col-sm-3">
									{!! Form::input('number', 'Lines[' . $i . '][amount]', $preEnteredLines[$i]['amount'], ['class' => 'form-control statement-amount', 'step' => 'any']) !!} 
								</div>
							</div>
						@endfor
						
						<div class="row total">
							<div class="col-sm-3 col-sm-offset-9">
								{!! Form::input('number', 'total', number_format($sum, 2, '.', ''), ['class' => 'form-control statement-total-amount', 'readonly' => 'readonly']) !!} 
							</div>
						</div>
										
					</div>
					<!-- /.box-body -->
					<div class="box-footer">{!! Form::submit('Update', ['class' => 'btn
						btn-primary form-control']) !!}</div>
					<!-- box-footer -->
					
				</div>    
			</div>
			<div class='col-md-6 col-sm-12'>
				<div class="box">
				  <div class="box-header with-border">
				    <h3 class="box-title">Metadata</h3>
				  </div><!-- /.box-header -->
				  <div class="box-body">
					     <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
			                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::date('datum', null, ['class' => 'form-control']) !!}
			                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>
			            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
			                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::text('omschrijving', null, ['class' => 'form-control']) !!}
			                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>
			            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
			                {!! Form::label('activum_id', 'Activum: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::select('activum_id', $assets, null, ['class' => 'form-control', 'placeholder' => ' - No asset - ']) !!}
			                    {!! $errors->first('activum_id', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>			            
			            <div class="form-group {{ $errors->has('opmerkingen') ? 'has-error' : ''}}">
			                {!! Form::label('opmerkingen', 'Opmerkingen: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
			                    {!! $errors->first('opmerkingen', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>
			            {!! Form::hidden('actief', '1') !!}
					
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
