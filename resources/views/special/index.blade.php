@extends('layouts/adminlte')

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Specials</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if(count($special) > 0)
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th><th>Statement description</th><th>Post</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($special as $item)
                                <tr>
                                    <td><a href="{{ url('special', $item->id) }}">{{ $item->name }}</a></td>
                                    <td>{{ $item->statement_description }}</td>
                                    <td>@post($item->Post)</td>
                                    <td>
                                        {!! Form::open([
                                            'method'=>'DELETE',
                                            'url' => ['special', $item->id],
                                            'style' => 'display:inline',
                                            'data-confirm' => 'Are you sure you want to delete this item?'
                                        ]) !!}
                                            <div class="btn-group btn-group-xs">
                                                <a class="btn btn-default btn-xs" href="{{ url('special/' . $item->id . '/edit') }}">
                                                    <i class="fa fa-fw fa-pencil"></i>
                                                </a>
                                                {!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}
                                            </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        No specials yet. Create one to get started
                    @endif
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $special->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Special</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('special/create') }}" class="btn btn-primary btn-sm">Add new special</a><br /><br />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
