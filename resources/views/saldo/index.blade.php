@extends('layouts/adminlte')
@section('page_title', "Amounts due")
@section('page_description', $filter == 'all' ? 'all' : 'open')

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Saldo</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Description</th><th>Opposing party</th><th class="amount">Amount</th><th>Since</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($amounts as $item)
			                <tr class="
			                	@if($item->isOpen()) 
			                		open-saldo
			                	@else
			                		closed-saldo
			                	@endif
			                ">
			                    <td><a href="{{ route( 'saldo.show', [ 'id' => $item->id ] ) }}">{{ $item->omschrijving }}</a></td>
			                    <td>{{ $item->Relation->bedrijfsnaam }}</td>
			                    <td class="amount">@amount($item->getOpenAmount())</td>
			                    
			                    <?php $daysOpen = $item->getStartDate() ? $item->getStartDate()->diffInDays($item->getEndDate()) : 0; ?>
			                    <td class="
			                    	@if($daysOpen > 45) 
			                    		amount-overdue-danger
			                    	@elseif($daysOpen > 30)
			                    		amount-overdue-warning
			                    	@endif
			                    ">{{ $daysOpen }} d</td>
			                    
			                    <td>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['saldo', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
			                            {!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}
			                        {!! Form::close() !!}
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $amounts->appends(['filter' => $filter ])->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ route('saldo.index', [ 'filter' => 'open']) }}" class="btn btn-primary">Open amounts</a>
                	<a href="{{ route('saldo.index', [ 'filter' => 'all']) }}" class="btn btn-default">All amounts</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
