@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Statement</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Datum</th><th>Omschrijving</th><th>Opmerkingen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $statement->id }}</td> <td> {{ $statement->datum }} </td><td> {{ $statement->omschrijving }} </td><td> {{ $statement->opmerkingen }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection