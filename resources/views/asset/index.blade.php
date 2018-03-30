@extends('layouts/adminlte')
@section('page_title', "Assets")

@section('content')
    <div class='row'>
        <div class='col-md-8 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Asset</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Omschrijving</th>
			                    <th>Aanschafdatum</th>
			                    <th class="amount">Aanschafwaarde</th>
			                    <th class="amount">Huidige waarde</th>
			                    <th class="amount">Afschrijving</th>
			                    <th>Status</th>
			                    <th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($asset as $item)
			                <tr>
			                    <td><a href="{{ url('asset', $item->id) }}">{{ $item->omschrijving }}</a></td>
			                    <td>{{ $item->aanschafdatum->format('d-m-Y') }}</td>
			                    <td class="amount">@amount($item->bedrag)</td>
			                    <td class="amount">@amount($item->amortization()->getCurrentValue())</td>
			                    <td class="amount">@if(!$item->amortization()->isFinished())
                                    @amount($item->amortization()->getAmount()) / {{ $item->amortization()->getPeriodDescription() }}
                                @endif</td>
			                    <td>
			                    	@if($item->amortization()->isFinished())
			                    		Amortized
			                    	@else
			                    		{{ $item->amortization()->getPeriodsAmortized() }} / {{ $item->afschrijvingsduur }} {{ $item->amortization()->getPeriodDescription() }}
			                    	@endif
			                    </td>
			                    <td>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['asset', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?',
                                        'class' => 'delete-asset'
			                        ]) !!}
			                        {!! Form::close() !!}

                                    <div class="btn-group">
                                        <a class="btn btn-default btn-xs" href="{{ url('asset/' . $item->id . '/edit') }}"><i class="fa fa-fw fa-pencil"></i></a>
                                        <a class="btn btn-default btn-xs" href="{{ url('asset/' . $item->id . '/statements') }}"><i class="fa fa-fw fa-eur"></i></a>
                                        <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu" role="menu">
                                            @if(!$item->amortization()->isFinished())
                                                <li><a class="" href="{{ url('asset/' . $item->id . '/finalize') }}"><i class="fa fa-fw fa-hourglass-end"></i> Finalize</a></li>
                                            @endif
                                            <li><a href="#" class="delete-link" onClick="$(this).parents('td').find( 'form.delete-asset').submit(); return false;"><i class="fa fa-fw fa-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>

			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $asset->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-4 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Asset</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('asset/create') }}" class="btn btn-primary btn-sm">Add New Asset</a><br /><br />
                	
                	A form could be added here, although it is out of scope for scaffolding.
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
