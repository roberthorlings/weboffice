<?php $numStatementLines = $item->StatementLines()->count(); ?>
<tr
	class="
		header-line
		{{ !$item->isBalanced() ? "not-balanced" : "" }}
		{{ $idx % 2 == 0 ? "even" : "odd" }}
	"
	>
	<td colspan="2">{{ $item->datum->format( 'd-m-Y') }}</td>
	<td colspan="2" class="description">
		{{ $item->omschrijving }}
	</td>
	<td class="remarks" rowspan="{{ $numStatementLines + 1 }}">
		{{ $item->opmerkingen }}
	</td>
	<td class="buttons" rowspan="{{ $numStatementLines + 1 }}">
		{!! Form::open([
				'method'=>'DELETE',
				'url' => ['statement', $item->id],
				'style' => 'display:inline',
				'data-confirm' => 'Are you sure you want to delete this statement?'
		]) !!}
		<div class="btn-group btn-group-xs">
			<a class="btn btn-default btn-xs" href="{{ url('statement/' . $item->id . '/edit') }}">
				<i class="fa fa-fw fa-pencil"></i>
			</a>
			{!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['class' => 'btn btn-danger btn-xs', 'title' => 'Delete statement', 'type' => 'submit']) !!}
		</div>
		{!! Form::close() !!}
	</td>
</tr>

@foreach($item->StatementLines()->orderBy('credit')->get() as  $statementLine)
	<tr	class="detail-line 
		{{ $idx % 2 == 0 ? 'even' : 'odd' }}
		{{ !$item->isBalanced() ? "not-balanced" : "" }}
		">
		<td>{{ $statementLine->credit ? 'Aan' : '' }}</td>
		<td><span class="post-number">{{ $statementLine->Post->nummer }}</span></td>
		<td><span class="post-description">{{ $statementLine->Post->omschrijving }}</span></td>
		<td class="amount">@amount($statementLine->bedrag)</td>
	</tr>
@endforeach
