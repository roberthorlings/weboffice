@extends('layouts/adminlte')

@section('content')

    {!! Form::model($special, [
        'method' => 'PATCH',
        'url' => ['special', $special->id],
        'class' => 'form-horizontal'
    ]) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Edit Account</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">
          <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
              {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-6">
                  {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
          <div class="form-group {{ $errors->has('statement_description') ? 'has-error' : ''}}">
              {!! Form::label('statement_description', 'Statement description: ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-6">
                  {!! Form::text('statement_description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  {!! $errors->first('statement_description', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
          <div class="form-group {{ $errors->has('post_id') ? 'has-error' : ''}}">
              {!! Form::label('post_id', 'Post: ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-6">
                  {!! Form::postSelect('post_id', $posts, null, ['class' => 'form-control', 'placeholder' => ' - No post selected - ']) !!}
                  {!! $errors->first('post_id', '<p class="help-block">:message</p>') !!}
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
