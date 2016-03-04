@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Posttype</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Naam</th><th>Omschrijving</th><th>Balanszijde</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $posttype->id }}</td> <td> {{ $posttype->type }} </td><td> {{ $posttype->omschrijving }} </td><td> {{ $posttype->balanszijde }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection