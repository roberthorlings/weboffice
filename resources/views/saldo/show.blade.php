@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary saldo-detail">
	  <div class="box-header with-border">
	    <h3 class="box-title">Saldo: {{ $saldo->omschrijving }}</h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th> <th>Description</th><th colspan="3">Amount</th>
                </tr>
            </thead>
            <tbody>
               	@foreach( $saldo->StatementLines as $statementLine )
					<tr	class="detail-line">
						<td>{{ $statementLine->Statement->datum->format('d-m-Y') }}</td>
						<td>{{ $statementLine->Statement->omschrijving }}</td>
						<td>{{ $statementLine->credit ? 'Credit' : 'Debet' }}</td>
						
						<td class="amount">
							@if(!$statementLine->credit)
								@amount($statementLine->bedrag)
							@endif
						</td>
						<td class="amount">
							@if($statementLine->credit)
								@amount($statementLine->bedrag)
							@endif
						</td>
					</tr>                	
               	@endforeach
            </tbody>
            <tfoot>
				<tr	class="total-line">
					<td></td>
					<td>
						Total
					</td>
					<td>
						{{ ucfirst($saldo->totalSide()) }}
					</td>
					<td class="amount">
						@if( $saldo->getOpenAmount() >= 0 ) 
							@amount($saldo->getOpenAmount())
						@endif
					</td>
					<td class="amount">
						@if( $saldo->getOpenAmount() < 0 ) 
							@amount(-$saldo->getOpenAmount())
						@endif
					</td>
				</tr>            
            </tfoot>    
        </table>
	    
	  </div><!-- /.box-body -->
	</div><!-- /.box -->


@endsection