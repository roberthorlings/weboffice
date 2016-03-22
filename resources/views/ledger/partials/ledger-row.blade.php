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

@foreach($ledger->getStatementLines() as $statementLine)
	<tr	class="detail-line 
		{{ $idx % 2 == 0 ? 'even' : 'odd' }}
		">
		<td>{{ $statementLine->Statement->datum->format('d-m-Y') }}</td>
		<td>{{ $statementLine->Statement->omschrijving }}</td>
		<td>{{ $statementLine->credit ? 'Credit' : 'Debet' }}</td>
		
		<td>
			@if(!$statementLine->credit)
				@amount($statementLine->bedrag)
			@endif
		</td>
		<td>
			@if($statementLine->credit)
				@amount($statementLine->bedrag)
			@endif
		</td>
	</tr>
@endforeach

<tr
	class="
		ledger-total-line
		{{ $idx % 2 == 0 ? "even" : "odd" }}
	"
	>
	<td></td>
	<td>
		Totaal
	</td>
	<td>
		{{ $ledger->totalSide() }}
	</td>
	<td>
		@if( $ledger->getTotal() >= 0 ) 
			@amount($ledger->getTotal())
		@endif
	</td>
	<td>
		@if( $ledger->getTotal() < 0 ) 
			@amount(-$ledger->getTotal())
		@endif
	</td>
</tr>