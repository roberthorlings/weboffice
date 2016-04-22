                <div class="box-header with-border">
                    <h3 class="box-title">{{$title}}</h3>
                    <span class="total">@amount(-$totalAmount)</span>
                    
                    @if(count($statementPart) > 0)
	                    <div class="box-tools pull-right">
	                        @if($collapsed)
	                        	<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Expand"><i class="fa fa-plus"></i></button>
	                        @else
	                        	<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
	                        @endif
	                    </div>
	                @endif
                </div>
                <div class="box-body">
						@foreach($statementPart as $category => $posts)
							<div class="row post-type">
		                		<div class="col-xs-7">
	                				{{$posts->getLabel()}}
	                			</div>
	                			<div class="col-xs-4 amount">
	                				@amount(-$posts->getTotal())
	                			</div>
	                		</div>
                			@foreach( $posts as $total )
			                	<div class="row post-total">
			                		<div class="col-xs-8">
		                				<a href="{{ route('ledger.index', [ 'post_id' => $total->getPost()->id ]) }}">
		                					@post($total->getPost())
		                				</a>
		                			</div>
		                			<div class="col-xs-4 amount">
		                				@amount(-$total->getSignedAmount())
		                			</div>
		                		</div>
                			@endforeach
	                	@endforeach
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<div class="row totals">
	               		<div class="col-xs-7">
               				{{$totalTitle}}
               			</div>
               			<div class="col-xs-4 amount">
	               			@amount(-$totalAmount)
                		</div>
                	</div>
                </div>
