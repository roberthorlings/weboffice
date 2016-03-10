<tr
	class="
		{{ $item->ingedeeld ? "booked" : "not-booked-yet" }}
		{{ $item->isSplitted() ? "splitted" : "single" }}
		{{ $idx % 2 == 0 ? "even" : "odd" }}
	"
	>
	<td>{{ $item->datum->format( 'd-m-Y') }}</td>
	<td>{{ $item->Account->omschrijving }}</td>
	<td>
		@if($item->ingedeeld)
			{{ $item->statement->omschrijving }}
		@else
			{{ $item->omschrijving }}
		@endif
	</td>
	<td>
		@if($item->ingedeeld && !$item->isSplitted())
			@post($item->getPost())
		@endif
	</td>
	<td class="amount {{ $item->bedrag < 0 ? 'negative' : 'positive' }}">
		@amount($item->bedrag)
	</td>
	<td>
		{!! Form::open([
				'method'=>'DELETE',
				'url' => ['transaction', $item->id],
				'style' => 'display:inline',
				'data-confirm' => 'Are you sure you want to delete this item?'
		]) !!}
		<div class="btn-group btn-group-xs">
			@if($item->ingedeeld)
				<a class="btn btn-default btn-xs" href="{{ url('transaction/' . $item->id . '/edit') }}">
					<i class="fa fa-fw fa-pencil"></i>
				</a>
	        @else
				<div class="btn-group">
	            	<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                	<i class="fa fa-fw fa-anchor"></i>
	                </button>
	                <ul class="dropdown-menu">
					    <li>
					        <a href="{{ url( 'transaction/' . $item->id . '/assign/invoice' ) }}">Betaalde factuur</a>
					    </li>
					    <li>
					        <a href="{{ url( 'transaction/' . $item->id . '/assign/transfer' ) }}">Overboeking eigen rekening</a>
					    </li>
					    <li>
					        <a href="{{ url( 'transaction/' . $item->id . '/assign/private' ) }}">Priv&eacute; boeking</a>
					    </li>
					    <li>
					        <a href="{{ url( 'transaction/' . $item->id . '/assign/costs_with_vat' ) }}">Kosten incl. BTW</a>
					    </li>    
					    <li>
					        <a href="{{ url( 'transaction/' . $item->id . '/assign/costs_without_vat' ) }}">Kosten excl. BTW</a>
					    </li>    
					    <li>
					        <a href="{{ url( 'transaction/' . $item->id . '/assign' ) }}">Anders</a>
					    </li>
	                </ul>
	            </div>
	        @endif
			{!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['class' => 'btn btn-danger btn-xs', 'title' => 'Delete transaction']) !!}
		</div>
		{!! Form::close() !!}
	</td>
</tr>

@if($item->isSplitted())
	@foreach($item->statement->statementlines as $index => $statementLine)
		@if($index > 0)
			<?php $signedAmount = $statementLine->getSignedAmount(); ?>
			<tr	class="{{ $idx % 2 == 0 ? 'even' : 'odd' }}">
				<td></td>
				<td></td>
				<td></td>
				<td>@post($statementLine->Post)</td>
				<td class="amount {{ $signedAmount < 0 ? 'negative' : 'positive' }}">@amount($signedAmount)</td>
				<td></td> 
			</tr>
		@endif
	@endforeach
@endif