@extends('layouts/adminlte')

@section('content')

    {!! Form::model($posttype, [
        'method' => 'PATCH',
        'url' => ['posttype', $posttype->id],
        'class' => 'form-horizontal'
    ]) !!}
	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Edit Posttype</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

		                <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                {!! Form::label('type', 'Naam: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('type', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('omschrijving', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('balanszijde') ? 'has-error' : ''}}">
                {!! Form::label('balanszijde', 'Balanszijde: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('balanszijde', array (
  '' => 'Debet',
  'credit' => 'Credit',
), null, ['class' => 'form-control', ]) !!}
                    {!! $errors->first('balanszijde', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('draagt_bij_aan_resultaat') ? 'has-error' : ''}}">
                {!! Form::label('draagt_bij_aan_resultaat', 'Draagt Bij Aan Resultaat: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                                <div class="checkbox">
                <label>{!! Form::radio('draagt_bij_aan_resultaat', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('draagt_bij_aan_resultaat', '0', true) !!} No</label>
            </div>
                    {!! $errors->first('draagt_bij_aan_resultaat', '<p class="help-block">:message</p>') !!}
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
