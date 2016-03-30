@for($i = 0; $i < $numLines; $i++)
	{!! Form::hidden('Lines[' . $i . '][id]', $preEnteredLines[$i]['id']) !!}
	<div class="row quote-line">
		<div class="col-sm-4">
			{!! Form::text('Lines[' . $i . '][titel]',  $preEnteredLines[$i]['titel'], ['class' => 'form-control']) !!}
		</div>
		<div class="col-sm-3">
			{!! Form::textarea('Lines[' . $i . '][inhoud]',  $preEnteredLines[$i]['inhoud'], ['class' => 'form-control']) !!}
		</div>
	</div>
@endfor