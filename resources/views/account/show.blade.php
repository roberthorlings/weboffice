@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Account</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Rekeningnummer</th><th>Omschrijving</th><th>Bank</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $account->id }}</td> <td> {{ $account->rekeningnummer }} </td><td> {{ $account->omschrijving }} </td><td> {{ $account->bank }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection