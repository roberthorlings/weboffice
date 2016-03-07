@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Relation</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Bedrijfsnaam</th><th>Contactpersoon</th><th>Adres</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $relation->id }}</td> <td> {{ $relation->bedrijfsnaam }} </td><td> {{ $relation->contactpersoon }} </td><td> {{ $relation->adres }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection