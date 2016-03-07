@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Transaction</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Omschrijving</th><th>Bedrag</th><th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->id }}</td> <td> {{ $transaction->omschrijving }} </td><td> {{ $transaction->bedrag }} </td><td> {{ $transaction->datum }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection