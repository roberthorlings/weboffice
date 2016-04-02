@extends('layouts/adminlte')
@section('page_title', "Projects")

@section('content')
    <div class='row'>
        <div class='col-md-10 col-sm-12'>
            <!-- Table box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Projects</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
			        <table class="table table-bordered table-striped table-hover projects">
			            <thead>
			                <tr>
			                    <th>Name</th><th>Relation</th><th>Status</th><th>Post</th><th colspan="2">Revenue</th><th></th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($project as $item)
			                <tr class="project-status-{{$item->status}}">
			                    <td><a href="{{ url('project', $item->id) }}">{{ $item->naam }}</a></td>
			                    <td>
			                    	@if($item->relatie_id)
			                    		<a href="{{ url('relation', $item->relatie_id) }}">{{ $item->Relation->bedrijfsnaam}}</a>
			                    	@endif
			                    </td>
			                    <td>{{ $item->getStatus() }}</td>
			                    <td>
			                    	@if($item->post_id)
			                    		@post($item->Post)
			                    	@endif
			                    </td>
			                    <td>
									<span class="total-revenue">@amount($item->getTotalRevenue())</span>
			                    </td>
			                    <td>
			                    	@if($item->hasRevenuePerHour()) 
										<span class="revenue-per-hour">@amount($item->getRevenuePerHour()) / hour</span>
									@endif
			                    </td>
			                    <td>
			                        {!! Form::open([
			                            'method'=>'DELETE',
			                            'url' => ['project', $item->id],
			                            'style' => 'display:inline',
			                            'data-confirm' => 'Are you sure you want to delete this item?'
			                        ]) !!}
				                    	<div class="btn-group btn-group-xs">
					                        <a class="btn btn-default btn-xs" href="{{ url('project/' . $item->id . '/edit') }}">
					                            <i class="fa fa-fw fa-pencil"></i>
					                        </a>
				                            {!! Form::button('<i class="fa fa-fw fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}
					                    </div>
			                        {!! Form::close() !!}
			                    </td>
			                </tr>
			            @endforeach
			            </tbody>
			        </table>
                </div><!-- /.box-body -->
                <div class="box-footer pagination-footer">
			        <div class="pull-right"> {!! $project->appends(['filter' => $filter])->render() !!} </div>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-2 col-sm-12'>
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Project</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                	<a href="{{ url('project/create') }}" class="btn btn-primary">Add New Project</a><br /><br />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection
