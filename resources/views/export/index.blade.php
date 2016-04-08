@extends('layouts/adminlte')
@section('page_title', "Export data")

@section('js')
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
@endsection
@section('content')
    <div class='row'>
        <div class='col-md-6 col-sm-12'>
            <div class="box box-primary">
                {!! Form::open([
                    'method'=>'POST',
                    'url' => route('export.pdf'),
                ]) !!}
                <div class="box-header with-border">
                    <h3 class="box-title">Export as PDF</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
				    <div class="form-group">
		                {!! Form::label('period', 'Period: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::text('period', $start->format( 'd-m-Y') . ' - ' . $end->format( 'd-m-Y'), ['class' => 'form-control default-date-range', 'readonly' => 'readonly', 'data-selector-start' => '#period-start', 'data-selector-end' => '#period-end'] ) !!}
		                    {!! Form::hidden('start', $start->format( 'Y-m-d'), ['id' => 'period-start'] ) !!}
		                    {!! Form::hidden('end', $end->format( 'Y-m-d'), ['id' => 'period-end'] ) !!}
		                </div>
		            </div>
				    <div class="form-group">
		                {!! Form::label('post_id', 'Post: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
                            @foreach($availableTypes as $key => $description)
                            <div class="checkbox">
				                <label>{!! Form::checkbox('option[' . $key . ']', '1', true) !!} {{$description}}</label>
				            </div>
				            @endforeach
		                </div>
		            </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
	                <div class="col-sm-8 col-sm-offset-4">
	                    <button class="btn btn-primary" title="Export"><i class="fa fa-file"></i> Export</button>
	                </div>
                </div>
                {!! Form::close() !!}                	
            </div><!-- /.box -->
		</div>        
        <div class='col-md-6 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Year overviews</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<ul>
                		@for($year = $endYear; $year >= $startYear; $year--)
                			<li><a href="{{ route('export.year', ['year' => $year]) }}">{{$year}}</a></li>
                		@endfor
                	</ul>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
