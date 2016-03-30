@extends('layouts/adminlte')
@section('js')
@endsection
@section('content')

    {!! Form::open(['url' => 'quote', 'class' => 'form-horizontal']) !!}

	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Quote metadata</h3>
			  </div><!-- /.box-header -->
				  <div class="box-body">
				  
            <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
                {!! Form::label('datum', 'Datum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('datum', $date, ['class' => 'form-control']) !!}
                    {!! $errors->first('datum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
	        <div class="form-group {{ $errors->has('offertenummer') ? 'has-error' : ''}}">
                {!! Form::label('offertenummer', 'Offertenummer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('offertenummer', $number, ['class' => 'form-control']) !!}
                    {!! $errors->first('offertenummer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('titel') ? 'has-error' : ''}}">
                {!! Form::label('titel', 'Titel: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('titel', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('titel', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

				    <div class="form-group">
		                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-9">
		                    {!! Form::relationProjectSelect('relation_project', $relations, $relation_project, ['class' => 'form-control', 'placeholder' => '']) !!}
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
			    <h3 class="box-title">Other</h3>
			  </div><!-- /.box-header -->
			  <div class="box-body">
		            <div class="form-group {{ $errors->has('totaalbedrag') ? 'has-error' : ''}}">
		                {!! Form::label('totaalbedrag', 'Totaalbedrag: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::number('totaalbedrag', null, ['class' => 'form-control']) !!}
		                    {!! $errors->first('totaalbedrag', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>
		            <div class="form-group {{ $errors->has('vervaldatum') ? 'has-error' : ''}}">
		                {!! Form::label('vervaldatum', 'Vervaldatum: ', ['class' => 'col-sm-3 control-label']) !!}
		                <div class="col-sm-6">
		                    {!! Form::date('vervaldatum', $expiry_date, ['class' => 'form-control']) !!}
		                    {!! $errors->first('vervaldatum', '<p class="help-block">:message</p>') !!}
		                </div>
		            </div>

			  </div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>

    
		<div class="box box-primary invoice-details">
			<div class="box-header with-border">
				<h3 class="box-title">Quote lines</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@include('quote/partials/quote-lines')
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				{!! Form::submit('Create', ['class' => 'btn	btn-primary form-control']) !!}
			</div>
			<!-- box-footer -->
			
		</div>    

    {!! Form::close() !!}

@endsection