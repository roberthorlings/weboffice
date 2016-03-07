@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Invoice</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Factuurnummer</th><th>Versie</th><th>Titel</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->id }}</td> <td> {{ $invoice->factuurnummer }} </td><td> {{ $invoice->versie }} </td><td> {{ $invoice->titel }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection