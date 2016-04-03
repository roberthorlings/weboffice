@extends('layouts/adminlte')
@section('page_title', $relation->bedrijfsnaam)

@section('content')

     <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">

              <h3 class="profile-username text-center">{{ $relation->bedrijfsnaam }}</h3>

              <p class="text-muted text-center">{{ $relation->contactpersoon }}</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Type</b> <a class="pull-right">{{ $relation->getRelationType() }}</a>
                </li>
              </ul>

              <a href="{{ route( 'relation.edit', [ 'id' => $relation->id ] ) }}" class="btn btn-primary btn-block"><b>Edit</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Overview</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Type</b> <a class="pull-right">{{ $relation->getRelationType() }}</a>
                </li>
                <li class="list-group-item">
                  <b># projects</b> <a class="pull-right">{{ $relation->Projects()->count() }}</a>
                </li>
                <li class="list-group-item">
                  <b># Working hours</b> <a class="pull-right">{{ $relation->getTotalWorkingHours() }}</a>
                </li>
                <li class="list-group-item">
                  <b>Total revenue</b> <a class="pull-right">@amount($relation->getTotalRevenue())</a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
              <li><a href="#projects" data-toggle="tab">Projects</a></li>
              <li><a href="#statistics" data-toggle="tab">Statistics</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="details">

              <div class="row">
              	<div class="col-sm-4 col-xs-12">
              		<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
              		<p>
	              		{{ $relation->adres }}<br />
	              		{{ $relation->postcode }} {{ $relation->plaats }}<br />
	              		{{ $relation->land }}
	              	</p>
              	</div>
              	<div class="col-sm-4 col-xs-12">
              		@if($relation->postadres)
	              		<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
	              		<p>
		              		{{ $relation->postadres }}<br />
		              		{{ $relation->postpostcode }} {{ $relation->postplaats }}<br />
		              		{{ $relation->postland }}
		              	</p>
	              	@endif
              	</div>
              	<div class="col-sm-4 col-xs-12">
	              	<strong><i class="fa fa-money margin-r-5"></i> Invoice Address</strong>
	              	<p>
              			{!! nl2br(e($relation->factuuradres)) !!}
              		</p>
              	</div>
              	
              </div>

              <hr>
              
              <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
              @if($relation->telefoon)
	              <p class="text-muted">
                	{{ $relation->telefoon }} 
              </p>
              @endif
              @if($relation->mobiel)
	              <p class="text-muted">
                	{{ $relation->mobiel }} 
              </p>
              @endif

              <hr>
			
			  @if($relation->fax)
              <strong><i class="fa fa-fax margin-r-5"></i> Fax</strong>

              <p class="text-muted">{{ $relation->fax }}</p>
				
              <hr>
              @endif

              <strong><i class="fa fa-globe margin-r-5"></i> Digital</strong>

              @if($relation->email)
	              <p class="text-muted">
                	<a href="mailto: {{ $relation->email }}">{{ $relation->email }}</a> 
              </p>
              @endif
              @if($relation->website)
	              <p class="text-muted">
                	<a href="http://{{ $relation->website }}">{{ $relation->website }}</a>
              </p>
              @endif

              <hr>

			  @if($relation->opmerkingen)
	              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
	
	              <p>{{ $relation->opmerkingen }}</p>
	          @endif
              
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="projects">
			        <table class="table table-bordered table-striped table-hover">
			            <thead>
			                <tr>
			                    <th>Name</th> <th>Status</th><th>Revenue</th><th>Revenue per hour</th>
			                </tr>
			            </thead>
			            <tbody>
			            @foreach($relation->Projects as $item)
			                <tr class="project-status-{{$item->status}}">
			                    <td><a href="{{ url('project', $item->id) }}">{{ $item->naam }}</a></td>
			                    <td>{{ $item->getStatus() }}</td>
			                    <td>
									<span class="total-revenue">@amount($item->getTotalRevenue())</span>
			                    </td>
			                    <td>
			                    	@if($item->hasRevenuePerHour()) 
										<span class="revenue-per-hour">@amount($item->getRevenuePerHour()) / hour</span>
									@endif
			                    </td>
			                </tr>
						@endforeach			            
			            </tbody>    
			        </table>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="statistics">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                      <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Read more</a>
                        <a class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-user bg-aqua"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                      <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                      </h3>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                      <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                      <div class="timeline-body">
                        Take me to your leader!
                        Switzerland is small and neutral!
                        We are more like Germany, ambitious and misunderstood!
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection