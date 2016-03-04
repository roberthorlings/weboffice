@extends('layouts/adminlte')

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Posttype</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Naam</th><th>Omschrijving</th><th>Balanszijde</th><th>Actions</th>
			                </tr>
			            </thead>
			            <tbody>
			            {{-- */$x=0;/* --}}
			            @foreach($posttype as $item)
			                {{-- */$x++;/* --}}
			                <tr>
			                    <td><a href="{{ url('posttype', $item->id) }}">{{ $item->type }}</a></td><td>{{ $item->omschrijving }}</td><td>{{ $item->balanszijde }}</td>
			                    <td>
			                        <a href="{{ url('posttype/' . $item->id . '/edit') }}">
			                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
			                        </a> /
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['posttype', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
			                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
			                        {!! Form::close() !!}
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $posttype->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Posttype</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('posttype/create') }}" class="btn btn-primary btn-sm">Add New Posttype</a><br /><br />
                	
                	A form could be added here, although it is out of scope for scaffolding.
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
