@extends('layouts/adminlte')
@section('js')
	{{HTML::script(asset('/assets/js/statements.form.js'))}}
@endsection
@section('page_title', "Cost declaration")

@section('content') 
{!! Form::open([ 
	'method' => 'POST', 
	'url' => route('statement.book-cost-declaration'), 
	'class' => 'form-horizontal' 
]) !!}

		<div class='row'>
			<div class='col-md-6 col-sm-12'>
				<div class="box box-primary">
				  <div class="box-header with-border">
				    <h3 class="box-title">Metadata</h3>
				  </div><!-- /.box-header -->
				  <div class="box-body">
					     <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
			                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-9">
			                    {!! Form::date('datum', $date, ['class' => 'form-control']) !!}
			                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>
			            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
			                {!! Form::label('omschrijving', 'Titel: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-9">
			                    {!! Form::text('omschrijving', null, ['class' => 'form-control']) !!}
			                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>
					    <div class="form-group">
			                {!! Form::label('project_id', 'Project: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-9">
			                    {!! Form::select('project_id', $projects, null, ['class' => 'form-control', 'placeholder' => ' - No project - ']) !!}
			                </div>
			            </div>
			            <div class="form-group {{ $errors->has('btw') ? 'has-error' : ''}}">
			                {!! Form::label('btw', 'Btw: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-9">
			                    {!! Form::select('btw', [ '0' => '0%', '6' => '6%', '21' => '21%' ], 21, ['class' => 'form-control']) !!}
			                    {!! $errors->first('btw', '<p class="help-block">:message</p>') !!}
			                </div>
			            </div>
			            <div class="form-group {{ $errors->has('handling') ? 'has-error' : ''}}">
			                {!! Form::label('handling', 'Todo: ', ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-9">
			                    {!! Form::select('handling', [ 'invest' => 'Invest', 'pay' => 'Pay' ], 'invest', ['class' => 'form-control']) !!}
			                    {!! $errors->first('handling', '<p class="help-block">:message</p>') !!}
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
					
				</div>    
			</div>
			<div class='col-md-6 col-sm-12'>
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Statement lines (excl. VAT)</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						@for($i = 0; $i < $numLines; $i++)
							{!! Form::hidden('Lines[' . $i . '][id]', $preEnteredLines[$i]['id']) !!} 
							<div class="row transaction-line">
								{!! Form::hidden('', 1, [ 'class' => 'statement-sign' ]) !!} 
								<div class="col-sm-9">
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
					<div class="box-footer">{!! Form::submit('Create', ['class' => 'btn
						btn-primary form-control']) !!}</div>
					<!-- box-footer -->
				</div><!-- /.box -->
			</div>
		</div>    

{!! Form::close() !!} 
@endsection
