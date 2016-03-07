@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Configuration</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Name</th><th>Value</th><th>Title</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $configuration->id }}</td> <td> {{ $configuration->name }} </td><td> {{ $configuration->value }} </td><td> {{ $configuration->title }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection