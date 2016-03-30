@for($i = 0; $i < $numLines; $i++)
	{!! Form::hidden('Lines[' . $i . '][id]', $preEnteredLines[$i]['id']) !!}
	<div class="row invoice-line">
		<div class="col-sm-4">
			{!! Form::hidden('Lines[' . $i . '][project_id]', $preEnteredLines[$i]['project_id'], [ 'class' => 'invoice-line-project-id' ]) !!}
			{!! Form::text('Lines[' . $i . '][omschrijving]',  $preEnteredLines[$i]['omschrijving'], ['class' => 'form-control invoice-line-description']) !!}
		</div>
		<div class="col-sm-3">
			{!! Form::text('Lines[' . $i . '][extra]',  $preEnteredLines[$i]['extra'], ['class' => 'form-control invoice-line-extra']) !!}
		</div>
		<div class="col-sm-1">
			{!! Form::input('number', 'Lines[' . $i . '][aantal]', $preEnteredLines[$i]['aantal'], ['class' => 'form-control invoice-line-number', 'step' => 'any']) !!}
		</div>
		<div class="col-sm-1">
			{!! Form::input('number', 'Lines[' . $i . '][prijs]', $preEnteredLines[$i]['prijs'], ['class' => 'form-control invoice-line-amount', 'step' => 'any']) !!}
		</div>
		<div class="col-sm-3">
			{!! Form::postSelect('Lines[' . $i . '][post_id]', $posts, $preEnteredLines[$i]['post_id'], ['class' => 'form-control invoice-line-post', 'placeholder' => ' - No post selected - ']) !!}
		</div>
	</div>
@endfor

<div class="row total">
	<div class="col-sm-1 col-sm-offset-8">
		{!! Form::input('number', 'totaalbedrag', null, ['class' => 'form-control invoice-line-total-amount', 'readonly' => 'readonly']) !!}
	</div>
</div>
