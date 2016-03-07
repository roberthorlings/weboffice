@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Travelexpense</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Van Naar</th><th>Bezoekadres</th><th>Km Begin</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $travelexpense->id }}</td> <td> {{ $travelexpense->van_naar }} </td><td> {{ $travelexpense->bezoekadres }} </td><td> {{ $travelexpense->km_begin }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection