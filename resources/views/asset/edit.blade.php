@extends('layouts/adminlte')

@section('content')

    {!! Form::model($asset, [
        'method' => 'PATCH',
        'url' => ['asset', $asset->id],
        'class' => 'form-horizontal'
    ]) !!}
	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Asset</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">
            <div class="form-group {{ $errors->has('aanschafdatum') ? 'has-error' : ''}}">
                {!! Form::label('aanschafdatum', 'Aanschafdatum: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('aanschafdatum', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('aanschafdatum', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
		    <div class="form-group {{ $errors->has('omschrijving') ? 'has-error' : ''}}">
                {!! Form::label('omschrijving', 'Omschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('omschrijving', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('omschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('bedrag') ? 'has-error' : ''}}">
                {!! Form::label('bedrag', 'Aanschafwaarde: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('bedrag', null, ['class' => 'form-control', 'step' => 'any']) !!}
                    {!! $errors->first('bedrag', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('restwaarde') ? 'has-error' : ''}}">
                {!! Form::label('restwaarde', 'Restwaarde: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('restwaarde', null, ['class' => 'form-control', 'step' => 'any']) !!}
                    {!! $errors->first('restwaarde', '<p class="help-block">:message</p>') !!}
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
	
	<div class="box box-default">
	  <div class="box-header with-border">
	    <h3 class="box-title">Amortization</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">
            <div class="form-group {{ $errors->has('begin_afschrijving') ? 'has-error' : ''}}">
                {!! Form::label('begin_afschrijving', 'Begin Afschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('begin_afschrijving', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('begin_afschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('afschrijvingsduur') ? 'has-error' : ''}}">
                {!! Form::label('afschrijvingsduur', 'Afschrijvingsduur (periodes): ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('afschrijvingsduur', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('afschrijvingsduur', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('afschrijvingsperiode') ? 'has-error' : ''}}">
                {!! Form::label('afschrijvingsperiode', 'Afschrijvingsperiode: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('afschrijvingsperiode', [ '1' => 'maandelijks', '3' => 'per kwartaal', '12' => 'jaarlijks' ], null, ['class' => 'form-control']) !!}
                    {!! $errors->first('afschrijvingsperiode', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('post_investering') ? 'has-error' : ''}}">
                {!! Form::label('post_investering', 'Post Investering: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                	{!! Form::postSelect('post_investering', $lists["posten"], null, ['class' => 'form-control', 'placeholder' => ' - No post selected - ', 'required' => 'required']) !!} 
                    {!! $errors->first('post_investering', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('post_afschrijving') ? 'has-error' : ''}}">
                {!! Form::label('post_afschrijving', 'Post Afschrijving: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                	{!! Form::postSelect('post_afschrijving', $lists["posten"], null, ['class' => 'form-control', 'placeholder' => ' - No post selected - ', 'required' => 'required']) !!} 
                    {!! $errors->first('post_afschrijving', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('post_kosten') ? 'has-error' : ''}}">
                {!! Form::label('post_kosten', 'Post Kosten: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                	{!! Form::postSelect('post_kosten', $lists["posten"], null, ['class' => 'form-control', 'placeholder' => ' - No post selected - ', 'required' => 'required']) !!} 
                    {!! $errors->first('post_kosten', '<p class="help-block">:message</p>') !!}
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
