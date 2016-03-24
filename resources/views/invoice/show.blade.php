@extends('layouts/adminlte')

@section('content')

	<div class="box box-primary document">
	  <div class="box-header with-border">
	    <h3 class="box-title">
	    	Invoice {{ $invoice->factuurnummer }}
            <span class="version">
               	v{{ $invoice->versie }}
            </span>
      </h3>
	  </div><!-- /.box-header -->
	  <div class="box-body">
            <div class="row">
                {!! Form::label('datum', 'Invoice date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $invoice->datum->format('d-m-Y') }}
                </div>
            </div>

            <div class="row">
                {!! Form::label('factuurnummer', 'Invoice number: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $invoice->factuurnummer }}
                    
                    <span class="version">
                    	v{{ $invoice->versie }}
                    </span>
                </div>
            </div>
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