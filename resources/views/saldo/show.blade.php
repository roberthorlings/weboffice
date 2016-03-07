@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Saldo</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Omschrijving</th><th>Relatie</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $saldo->id }}</td> <td> {{ $saldo->omschrijving }} </td><td> {{ $saldo->relatie_id }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection