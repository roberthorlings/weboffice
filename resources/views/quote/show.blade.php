@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary">
	  <div class="box-header with-border">
	    <h3 class="box-title">Quote</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Offertenummer</th><th>Versie</th><th>Titel</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $quote->id }}</td> <td> {{ $quote->offertenummer }} </td><td> {{ $quote->versie }} </td><td> {{ $quote->titel }} </td>
                </tr>
            </tbody>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection