<tr
	class="
		header-line
		{{ $idx % 2 == 0 ? "even" : "odd" }}
	"
	>
	<td class="post-number">{{ $ledger->getPost()->nummer }}</td>
	<td colspan="4" class="post-description">
		{{ $ledger->getPost()->omschrijving }}
	</td>
</tr>

@if(abs($ledger->getInitial()) > 0)
	<tr	class="initial detail-line 
		{{ $idx % 2 == 0 ? 'even' : 'odd' }}
		">
		<td>-</td>
		<td>Van balans</td>
		<td>{{ ucfirst($ledger->initialSide() ) }}</td>
		
		<td class="amount">
			@if( $ledger->getInitial() >= 0 ) 
				@amount($ledger->getInitial())
			@endif
		</td>
		<td class="amount">
			@if( $ledger->getInitial() < 0 ) 
				@amount(-$ledger->getInitial())
			@endif
		</td>
	</tr>
@endif

@foreach($ledger->getStatementLines() as $statementLine)
	<tr	class="detail-line 
		{{ $idx % 2 == 0 ? 'even' : 'odd' }}
		">
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

<tr
	class="
		total-line
		{{ $idx % 2 == 0 ? "even" : "odd" }}
	"
	>
	<td></td>
	<td>
		Totaal
	</td>
	<td>
		{{ ucfirst($ledger->totalSide()) }}
	</td>
	<td class="amount">
		@if( $ledger->getTotal() >= 0 ) 
			@amount($ledger->getTotal())
		@endif
	</td>
	<td class="amount">
		@if( $ledger->getTotal() < 0 ) 
			@amount(-$ledger->getTotal())
		@endif
	</td>
</tr>