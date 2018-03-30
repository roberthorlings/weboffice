@extends('layouts/adminlte')

@section('content')
{!! Form::model($asset, [
'method' => 'post',
'url' => ['asset', $asset->id, 'finalize'],
'class' => 'form-horizontal'
]) !!}
<div class="row">
    <div class="col-sm-6 col-xs-12">
        <div class="box box-primary asset-booking">
            <div class="box-header with-border">
                <h3 class="box-title">Finalize amortization for asset {{$asset->omschrijving}}</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('date', 'Date for finalization: ', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('remainder', 'Remainder amount: ', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::number('remainder', $asset->restwaarde, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('post_id', 'Book remainder on: ', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::postSelect('post_id', null, null, ['class' => 'form-control', 'placeholder' => ' - No post selected - ']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-sm-6 col-xs-12">
        <div class="box box-primary asset-booking">
            <div class="box-header with-border">
                <h3 class="box-title">Current status</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3"><label>Aanschafdatum</label></div>
                    <div class="col-sm-6">{{ $asset->aanschafdatum->format('d-m-Y') }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><label>Aanschafwaarde</label></div>
                    <div class="col-sm-6">@amount($asset->bedrag)</div>
                </div>

                <div class="row">
                    <div class="col-sm-3"><label>Huidige waarde</label></div>
                    <div class="col-sm-6">@amount($asset->amortization()->getCurrentValue())</div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>

</div>
{!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
{!! Form::close() !!}

@endsection
