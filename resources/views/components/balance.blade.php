	<div class="row details">
		@foreach($balance->getBalance() as $side => $posts)
			<div class="col-xs-6 side side-{{$side}}">
	           @foreach( $posts as $total )
				    <div class="row post-total">
				        <div class="col-xs-8 no-padding">
			            	@post($total->getPost())
			            </div>
			            <div class="col-xs-4 amount">
			            	@amount($total->getAmount())
			        	</div>
			    	</div>
	        	@endforeach
			</div>
       	@endforeach
   	</div>
  	<div class="row totals">
		@foreach($balance->getTotals() as $side => $total)
       		<div class="col-xs-6 side side-{{$side}}">
              	<div class="row">
           			<div class="col-xs-4 col-xs-offset-8 amount">
          				@amount($total)
           			</div>
           		</div>
       		</div>
       	@endforeach
   	</div>
