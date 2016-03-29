@extends('layouts/adminlte')
@section('page_title', "Invoices")
@section('page_description', toTimespan($filter['start'], $filter['end']))

@section('js')
	{{HTML::script(asset('/assets/js/date-range-filter.js'))}}
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Invoices</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover documents invoices">
			            <thead>
			                <tr>
			                    <th>Date</th><th>#</th><th>Version</th><th>Type</th><th>Relation</th><th>Title</th><th>Amount</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($invoices as $item)
			                <tr class="
			                	{{ $item->definitief ? 'final' : 'concept' }}
			                ">
			                    <td>{{ $item->datum->format('d-m-Y')}}</td>
			                    <td>{{ $item->factuurnummer }}</td>
			                    <td>{{ $item->versie }}</td>
			                    <td>{{ $item->uurtje_factuurtje ? 'project' : 'default' }}</td>
			                    <td>{{ $item->Relation ? $item->Relation->bedrijfsnaam : ''}}</td>
			                    <td><a href="{{ url('invoice', $item->id) }}" class="title">{{ $item->titel }}</a></td>
			                    <td class="amount">@amount($item->totaalbedrag)</td>
			                    <td align="right">
			                        @if(!$item->definitief)
				                        {!! Form::open([
				                            'method'=>'POST',
				                            'url' => ['invoice', $item->id, 'mark_as_final'],
				                            'class' => 'mark_as_final'
				                        ]) !!}
			                        	{!! Form::close() !!}
						            @endif
			                    
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['invoice', $item->id],
			                            'data-confirm' => 'Are you sure you want to delete this item?',
				                        'class' => 'delete'
			                        ]) !!}
			                        {!! Form::close() !!}
			                        
			                          <div class="btn-group">
							             <a class="btn btn-default btn-xs" href="{{ route('invoice.edit', [ 'id' => $item->id ]) }}" title="Edit"><i class="fa fa-pencil fa-fw"></i></a></li>
							             <a class="btn btn-default btn-xs" href="{{ route('invoice.pdf', [ 'id' => $item->id ]) }}" title="Download as PDF"><i class="fa fa-download fa-fw"></i></a></li>
							             <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
							             <ul class="dropdown-menu" role="menu">
			                           		<li><a href="{{ route( 'invoice.statement', [ 'id' => $item->id ]) }}"><i class="fa fa-fw fa-bookmark"></i> Create statement</a></li>
			                        		@if(!$item->definitief)
				                           		<li><a href="#" onClick="$(this).parents('td').find( 'form.mark_as_final').submit(); return false;"><i class="fa fa-fw fa-anchor"></i> Mark as final</a></li>
				                           	@endif
				                           <li><a href="#" class="delete-link" onClick="$(this).parents('td').find( 'form.delete').submit(); return false;"><i class="fa fa-fw fa-trash"></i> Delete</a></li>
							             </ul>
			                          </div>
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $invoices->appends(paginationSafeFilter($filter))->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <div class="box">
                {!! Form::model($filter, [
                    'method'=>'GET',
                    'url' => ['invoice'],
                    'class' => 'filter'
                ]) !!}
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
				    <div class="form-group">
		                {!! Form::label('period', 'Period: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::text('period', $filter['start']->format( 'd-m-Y') . ' - ' . $filter['end']->format( 'd-m-Y'), ['class' => 'form-control date-range-input', 'readonly' => 'readonly', 'data-selector-start' => '#filter-period-start', 'data-selector-end' => '#filter-period-end'] ) !!}
		                    {!! Form::hidden('start', $filter['start']->format( 'Y-m-d'), ['id' => 'filter-period-start'] ) !!}
		                    {!! Form::hidden('end', $filter['end']->format( 'Y-m-d'), ['id' => 'filter-period-end'] ) !!}
		                </div>
		            </div>
				    <div class="form-group">
		                {!! Form::label('relation_project', 'Klant: ', ['class' => 'col-sm-4 control-label']) !!}
		                <div class="col-sm-8">
		                    {!! Form::relationProjectSelect('', $relations, array_key_exists( 'relation_project', $filter ) ? $filter['relation_project'] : null, ['class' => 'form-control', 'placeholder' => '']) !!}
		                </div>
		            </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
	                <div class="col-sm-8 col-sm-offset-4">
	                    <button class="btn btn-primary" title="Filter"><i class="fa fa-filter"></i> Filter</button>
	                </div>
                </div>
                {!! Form::close() !!}                	
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
