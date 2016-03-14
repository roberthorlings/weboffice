<tr
	class="
		{{ $item->ingedeeld ? "booked" : "not-booked-yet" }}
		{{ $item->Statement && !$item->Statement->isBalanced() ? "not-balanced" : "" }}
		{{ $item->isSplitted() ? "splitted" : "single" }}
		{{ $idx % 2 == 0 ? "even" : "odd" }}
	"
	>
	<td>{{ $item->datum->format( 'd-m-Y') }}</td>
	<td>{{ $item->Account->omschrijving }}</td>
	<td class="description">
		@if($item->ingedeeld)
			{{ $item->statement->omschrijving }}
		@else
			{{ $item->omschrijving }}
		@endif
	</td>
	<td>
		@if($item->ingedeeld)
			@post($item->Statement->StatementLines[1]->Post)
		@endif
	</td>
	<td class="amount {{ $item->bedrag < 0 ? 'negative' : 'positive' }}">
		@if($item->ingedeeld)
			@amount($item->Statement->StatementLines[1]->bedrag)
		@else
			@amount($item->bedrag)
		@endif
	</td>
	<td class="buttons">
		@if($item->ingedeeld)
			{!! Form::open([
					'method'=>'DELETE',
					'url' => ['transaction', $item->id, 'statement'],
					'style' => 'display:inline',
					'data-confirm' => 'Are you sure you want to delete the statement for this transaction?'
			]) !!}
			<div class="btn-group btn-group-xs">
				<a class="btn btn-default btn-xs" href="{{ url('transaction/' . $item->id . '/edit') }}">
					<i class="fa fa-fw fa-pencil"></i>
				</a>
				{!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['class' => 'btn btn-danger btn-xs', 'title' => 'Delete statement', 'type' => 'submit']) !!}
			</div>
			{!! Form::close() !!}
        @else
			{!! Form::open([
					'method'=>'DELETE',
					'url' => ['transaction', $item->id],
					'style' => 'display:inline',
					'data-confirm' => 'Are you sure you want to delete this item?'
			]) !!}
			<div class="btn-group btn-group-xs">
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
				{!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['class' => 'btn btn-danger btn-xs', 'title' => 'Delete transaction', 'type' => 'submit']) !!}
			</div>
			{!! Form::close() !!}
        @endif
        
	</td>
</tr>

@if($item->isSplitted())
	@foreach($item->statement->statementlines as $index => $statementLine)
		@if($index > 1)
			<?php $signedAmount = $statementLine->getSignedAmount(); ?>
			<tr	class="detailRow 
				{{ $idx % 2 == 0 ? 'even' : 'odd' }}
				{{ $item->Statement && !$item->Statement->isBalanced() ? "not-balanced" : "" }}
				">
				<td></td>
				<td></td>
				<td> &#8627;</td>
				<td>@post($statementLine->Post)</td>
				<td class="amount {{ $signedAmount > 0 ? 'negative' : 'positive' }}">@amount($statementLine->bedrag)</td>
				<td></td> 
			</tr>
		@endif
	@endforeach
@endif