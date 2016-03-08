$(function() {
	// Add onchange handler for visiting address
	$('.visiting-address').on('blur', function() {
		var el = $(this);
		
		// Don't do anything on empty value
		if(el.val() == "")
			return;
		
		// If we have a distance already, don't compute anything
		if($( '.travel-distance' ).val() != "")
			return;
		
		// Determine self address
		$.ajax(el.data( 'self-url'), { headers: { 'Accept': 'application/json' } })
			.then(function(self) {
				var origin = self.adres + ", " + self.postcode + " " + self.plaats; 
				var destination = el.val();
				var travelMode = el.parents( 'form' ).find( '.travel-method' ).val() == 'auto' ? google.maps.TravelMode.DRIVING : google.maps.TravelMode.WALKING;
				
				computeDistance({ origin: origin, destination: destination, travelMode: travelMode }, function(origin, destination, distance) {
					if(distance > 0) {
						$( '.travel-distance' ).val(Math.ceil(distance * 2 / 1000));
					}
				});
			})
		
	});
	
	// If both travel distance and start are given, automatically enter data in the end
	$('.travel-start').on('blur', function() {
		var start = $(this).val();
		var distance = $('.travel-distance').val();
		var end = $('.travel-end').val()
		
		if( start && distance && !end ) {
			$('.travel-end').val( parseInt(start) + parseInt(distance) );
		}
	});
	
});
