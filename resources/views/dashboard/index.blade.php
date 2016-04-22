@extends('layouts/adminlte')

@section('content')
    <div class='row'>
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Working hours</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	@foreach($workingHours as $month => $items)
                		<h4>{{$month}}</h4>
                		<ul class="workinghour-overview">
	                		@foreach($items as $item)
	                			 <li><span class="relation">{{$item['relation']->bedrijfsnaam}}</span> <span class="total">@duration($item['total'])</span></li>
	                		@endforeach
	                	</ul>
                	@endforeach
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Amounts due</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Description</th><th>Opposing party</th><th class="amount">Amount</th><th>Since</th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($saldos as $item)
			                <tr class="open-saldo">
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
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
	</div>
    <div class='row'>
        
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-primary balance">
                <div class="box-header with-border">
                    <h3 class="box-title">Balance</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	@include('components/balance')
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-secondary results">
               	@include('results/partials/result-overview', ['collapsed' => false, 'title' => 'Profit and loss ' . $profitAndLossStatement->getEnd()->format('Y'), 'statementPart' => $profitAndLossStatement->getResults(), 'totalTitle' => 'Results', 'totalAmount' => $profitAndLossStatement->getResultTotal() ])
            </div><!-- /.box -->
        </div><!-- /.col -->
        
    </div><!-- /.row -->
@endsection