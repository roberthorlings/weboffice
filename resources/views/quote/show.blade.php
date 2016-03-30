@extends('layouts/adminlte')

@section('content')
                        
	<div class="box box-primary document">
	  <div class="box-header with-border">
	    <h3 class="box-title">
	    	Offerte
	    	{{ $quote->offertenummer}}
            <span class="version">
               	v{{ $quote->versie }}
            </span>
      	</h3>
      	<div class="box-tools pull-right">
          <div class="btn-group">
             <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
             <ul class="dropdown-menu" role="menu">
               <li><a href="{{ route('quote.edit', [ 'id' => $quote->id ]) }}"><i class="fa fa-pencil fa-fw"></i>Edit</a></li>
               <li><a href="{{ route('quote.pdf', [ 'id' => $quote->id ]) }}"><i class="fa fa-download fa-fw"></i>Download as PDF</a></li>
             </ul>
		  </div>
        </div>
	  </div><!-- /.box-header -->
	  <div class="box-body">
            <div class="row">
                {!! Form::label('datum', 'Date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $quote->datum->format('d-m-Y') }}
                </div>
            </div>
            <div class="row">
                {!! Form::label('offertenummer', 'Number: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $quote->offertenummer }}
                    
                    <span class="version">
                    	v{{ $quote->versie }}
                    </span>
                </div>
            </div>
            <div class="row">
                {!! Form::label('relatie', 'Customer: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $quote->Relation ? $quote->Relation->bedrijfsnaam : '-' }}
                </div>
            </div>
            @if($quote->Project)
	            <div class="row">
	                {!! Form::label('project', 'Project: ', ['class' => 'col-sm-3 control-label']) !!}
	                <div class="col-sm-6">
	                    {{ $quote->Project->naam }}
	                </div>
	            </div>
			@endif
			
            <div class="row">
                {!! Form::label('vervaldatum', 'Expiry date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {{ $quote->vervaldatum->format('d-m-Y') }}
                </div>
            </div>
            <div class="row">
                {!! Form::label('totaalbedrag', 'Total amount: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    @amount($quote->totaalbedrag)
                </div>
            </div>
			
	  </div><!-- /.box-body -->
	</div><!-- /.box -->

	<div class="box box-default quote-details">
	  <div class="box-body">
         @foreach($quote->getAllLines() as $quoteLine)
	  		<h4>{{$quoteLine->titel}}</h4>
	  		<p>
	  			{{$quoteLine->inhoud}}
	  		</p>
         @endforeach
	  </div><!-- /.box-body -->
	</div><!-- /.box -->
@endsection
