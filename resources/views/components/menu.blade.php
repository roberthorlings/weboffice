@foreach($items as $item)
  <li@lm-attrs($item) @if($item->hasChildren())class ="treeview"@endif @lm-endattrs>
    @if($item->link) <a@lm-attrs($item->link) @lm-endattrs href="{!! $item->url() !!}">
      @if(array_key_exists('icon', $item->attributes))
      	<i class="fa {{$item->attributes['icon']}}"></i>
	  @endif 
      {!! $item->title !!}
      @if($item->hasChildren()) <i class="fa fa-angle-left pull-right"></i> @endif
    </a>
    @else
      {!! $item->title !!}
    @endif
    
    @if($item->hasChildren())
      <ul class="treeview-menu">
        @include('components/menu', ['items' => $item->children()])
      </ul> 
    @endif
  </li>
  @if($item->divider)
  	<li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
  @endif
@endforeach
