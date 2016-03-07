@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Asset</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Omschrijving</th><th>Aanschafdatum</th><th>Begin Afschrijving</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $asset->id }}</td> <td> {{ $asset->omschrijving }} </td><td> {{ $asset->aanschafdatum }} </td><td> {{ $asset->begin_afschrijving }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection