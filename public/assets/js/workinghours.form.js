$(function() {
	function retrieveDistance() {
		var el = $('.visiting-address');

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
	}
	
	function updateEndOdometer() {
		var start = $('.travel-start').val();
		var distance = $('.travel-distance').val();
		var end = $('.travel-end').val()
		
		if( start && distance && !end ) {
			$('.travel-end').val( parseInt(start) + parseInt(distance) );
		}
	}
	
	
	// Add onchange handler for visiting address
	$('.visiting-address').on('blur', retrieveDistance);
	
	// If both travel distance and start are given, automatically enter data in the end
	$('.travel-start').on('blur', updateEndOdometer);
	
	// Enable choosing an address used before
	$( ".addresses-used-before li a" ).on( "click", function() {
		var linkData = $(this).data();
		
		// Set address properties
		$('.travel-method').val(linkData.travelmethod);
		$('.trip').val(linkData.trip);
		$('.visiting-address').val(linkData.address);

		// Reset distance to trigger computation
		$('.travel-distance').val("");
		
		retrieveDistance();
	})
	
});
