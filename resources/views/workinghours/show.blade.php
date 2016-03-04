@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Workinghour</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Datum</th><th>Begintijd</th><th>Eindtijd</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $workinghour->id }}</td> <td> {{ $workinghour->datum }} </td><td> {{ $workinghour->begintijd }} </td><td> {{ $workinghour->eindtijd }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection