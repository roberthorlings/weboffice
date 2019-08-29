@extends('layouts/adminlte')

@section('content')

	<div class="box">
	  <div class="box-header with-border">
	    <h3 class="box-title">Transactions imported</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">
	  	<ul class="import-results">
		  	@foreach($results as $accountId => $info)
				@if(array_key_exists($accountId, $accounts))
					<li class="bank_{{ $accounts[$accountId]->bank }}">
						<span class="rekeningnummer">{{ $accounts[$accountId]->getFormattedNumber() }}</span>
						<span class="rekening">{{ $accounts[$accountId]->omschrijving }}</span><br />
						<span class="count gelukt">{{ $info[ "succesful" ] }} transactions are imported.</span>
						<span class="count mislukt">{{ $info[ "existing" ] }} transactions already existed in the database.</span>
					</li>
				@endif
			@endforeach
		</ul>
	  </div><!-- /.box-body -->
	  <div class="box-footer">
		<a class="btn btn-primary" href="{{ url('transaction') }}">Back to transactions</a>
	  </div><!-- box-footer -->
	</div><!-- /.box -->

    {!! Form::close() !!}

@endsection
