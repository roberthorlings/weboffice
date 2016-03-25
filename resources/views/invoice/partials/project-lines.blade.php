		@for($i = 0; $i < $numProjects; $i++)
			{!! Form::hidden('Projects[' . $i . '][id]', $preEnteredProjects[$i]['id']) !!} 
			<div class="row invoice-project">
				<div class="col-sm-4">
					{!! Form::select('Projects[' . $i . '][project_id]', $projects,  $preEnteredProjects[$i]['project_id'], ['class' => 'form-control project-invoice-select', 'placeholder' => ' - Select project - ']) !!} 
				</div>
				<div class="col-sm-4">
                    {!! Form::text('Projects[' . $i . '][period]', $preEnteredProjects[$i]['start']->format( 'd-m-Y') . ' - ' . $preEnteredProjects[$i]['end']->format( 'd-m-Y'), ['class' => 'form-control date-range-input', 'readonly' => 'readonly', 'data-selector-start' => '#project-' . $i . '-start', 'data-selector-end' => '#project-' . $i . '-end'] ) !!}
                    {!! Form::hidden('Projects[' . $i . '][start]', $preEnteredProjects[$i]['start']->format( 'Y-m-d'), ['id' => 'project-' . $i . '-start'] ) !!}
                    {!! Form::hidden('Projects[' . $i . '][end]', $preEnteredProjects[$i]['end']->format( 'Y-m-d'), ['id' => 'project-' . $i . '-end'] ) !!}
				</div>
				<div class="col-sm-4">
					{!! Form::select('Projects[' . $i . '][hours_overview_type]', ['default' => 'Default', 'short' => 'Short', 'none' => 'None'],  $preEnteredProjects[$i]['hours_overview_type'], ['class' => 'form-control']) !!} 
				</div>
			</div>
		@endfor
