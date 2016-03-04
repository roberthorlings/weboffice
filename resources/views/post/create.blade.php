@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'post', 'class' => 'form-horizontal']) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Create new Post</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('nummer') ? 'has-error' : ''}}">
                {!! Form::label('nummer', 'Nummer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('nummer', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('nummer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('omschrijving', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('percentage_aftrekbaar') ? 'has-error' : ''}}">
                {!! Form::label('percentage_aftrekbaar', 'Percentage Aftrekbaar: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('percentage_aftrekbaar', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('percentage_aftrekbaar', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('post_type_id') ? 'has-error' : ''}}">
                {!! Form::label('post_type_id', 'Post type: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('post_type_id', $lists["post_type_id"], null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('post_type_id', '<p class="help-block">:message</p>') !!}
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