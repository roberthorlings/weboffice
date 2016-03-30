@extends('layouts/adminlte')

@section('content')
                        
    {!! Form::open([
         'method'=>'POST',
         'url' => route( 'invoice.statement', [ 'id' => $invoice->id ]),
         'class' => 'create-statement'
    ]) !!}
    {!! Form::close() !!}
	<div class="box box-primary document">
	  <div class="box-header with-border">
	    <h3 class="box-title">
	    	@if($invoice->creditfactuur)
	    		Creditnote
	    	@else
	    		Invoice
	    	@endif
	    	{{ $invoice->factuurnummer }}
            <span class="version">
               	v{{ $invoice->versie }}
            </span>
      	</h3>
      	<div class="box-tools pull-right">
          <div class="btn-group">
             <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
             <ul class="dropdown-menu" role="menu">
               <li><a href="{{ route('invoice.edit', [ 'id' => $invoice->id ]) }}"><i class="fa fa-pencil fa-fw"></i>Edit</a></li>
               <li><a href="#" onClick="$('form.create-statement').submit(); return false;"><i class="fa fa-fw fa-bookmark"></i> Create statement</a></li>
               <li><a href="{{ route('invoice.pdf', [ 'id' => $invoice->id ]) }}"><i class="fa fa-download fa-fw"></i>Download as PDF</a></li>
             </ul>
		  </div>
        </div>
	  </div><!-- /.box-header -->
	  <div class="box-body">
            <div class="row">
                {!! Form::label('datum', 'Date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $invoice->datum->format('d-m-Y') }}
                </div>
            </div>
            <div class="row">
                {!! Form::label('factuurnummer', 'Number: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $invoice->factuurnummer }}
                    
                    <span class="version">
                    	v{{ $invoice->versie }}
                    </span>
                </div>
            </div>
            
            @if($invoice->creditfactuur)
	            <div class="row">
	                {!! Form::label('datum', 'Original invoice: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    Invoice {{$invoice->oorspronkelijk_factuurnummer}} (dd {{$invoice->oorspronkelijk_datum->format('d-m-Y')}})
	                </div>
	            </div>
	         @endif
            
            <div class="row">
                {!! Form::label('relatie', 'Customer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! $invoice->Relation ? nl2br(e($invoice->Relation->factuuradres)) : '-' !!}
                </div>
            </div>
            
            @if($invoice->Project)
	            <div class="row">
	                {!! Form::label('project', 'Project: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {{ $invoice->Project->naam }}
	                </div>
	            </div>
			@endif
            @if($invoice->referentie)
	            <div class="row">
	                {!! Form::label('referentie', 'Reference: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {{ $invoice->referentie }}
	                </div>
	            </div>
			@endif
		
	  </div><!-- /.box-body -->
	</div><!-- /.box -->

	<div class="box box-default document">
	  <div class="box-body">
	     <table class="table table-bordered table-striped table-hover document-lines">
            <tbody>
            	@foreach($invoice->InvoiceLines as $invoiceLine)
	                <tr>
	                    <td>
	                    	{{ $invoiceLine->omschrijving }}
	                  		@if($invoiceLine->extra)
	                  			<br />
	                  			<small>{{ $invoiceLine->extra }}</small>
	                  		@endif
	                    </td>
	                    <td> {{ (float) $invoiceLine->aantal }} </td>
	                    <td class="amount"> @amount($invoiceLine->prijs) </td>
	                    <td class="amount"> @amount($invoiceLine->getSubtotal()) </td>
	                </tr>
	                
	            @endforeach
            </tbody>
            <tfoot>
            	@if($invoice->btw)
	                <tr>
	                    <td>Total (excl. VAT)</td><td></td><td></td><td class="amount"> @amount($invoice->getSubtotal()) </td>
	                </tr>
	                <tr>
	                    <td>VAT {{ $invoice->getVATPercentage()}}%</td><td></td><td></td><td class="amount"> @amount($invoice->getVAT()) </td>
	                </tr>
	            @endif
                <tr class="totals">
                    <td>
                    	Total 
                    	@if($invoice->btw)
                    		(incl. VAT)
                    	@endif
                    </td>
                    <td></td><td></td>
                    <td class="amount"> @amount($invoice->getTotal())</td>
                </tr>
            </tfoot>                
        </table>
	  
	  </div><!-- /.box-body -->
	</div><!-- /.box -->
@endsection