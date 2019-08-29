@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Special</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Statement description</th><th>Post</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $special->id }}</td>
                    <td>{{ $special->name }}</td>
                    <td>{{ $special->statement_description }}</td>
                    <td>@post($special->Post)</td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection
