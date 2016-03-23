@extends('layouts/adminlte')

@section('content')

    {!! Form::open(['url' => 'transaction/import', 'files' => true, 'class' => 'form-horizontal']) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Import data</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">
	  		<p>You can upload a file with transactions as downloaded from you bank. Supported file types are</p>
	  		<ul>
	  			<li>Rabobank CSV format</li>
	  			<li>Rabobank MUT.ASC format</li>
	  			<li>ABN AMRO CSV format</li>
	  			<li>ING CSV format</li>
	  		</ul>
            <div class="form-group {{ $errors->has('datum') ? 'has-error' : ''}}">
                {!! Form::label('transactions', 'File: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::file('transactions', null, ['class' => 'form-control']) !!}
                </div>
            </div>
	  </div><!-- /.box-body -->
	  <div class="box-footer">
            {!! Form::submit('Import', ['class' => 'btn btn-primary form-control']) !!}
	  </div><!-- box-footer -->
	</div><!-- /.box -->

    {!! Form::close() !!}

@endsection