@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Post</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Nummer</th><th>Omschrijving</th><th>Percentage Aftrekbaar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $post->id }}</td> <td> {{ $post->nummer }} </td><td> {{ $post->omschrijving }} </td><td> {{ $post->percentage_aftrekbaar }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection