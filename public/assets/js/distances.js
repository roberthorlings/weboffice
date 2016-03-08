/**
 * Computes the distance between origin and destination
 * @param origin		Origin of the trip
 * @param destination	Trip destination
 * @param unitSystem	google.maps.UnitSystem.METRIC or google.maps.UnitSystem.IMPERIAL
 * @param travelMode	google.maps.TravelMode.DRIVING or google.maps.TravelMode.WALKING, google.maps.TravelMode.BICYCLING
 * @param callback		Callback method that is called with the results. Parameters: origin, destination, distance (in meters)
 * @return
 */
function computeDistance( options, callback ) {
	var settings = $.extend({
		origin: null,
		destination: null,
		unitSystem: google.maps.UnitSystem.METRIC,
		travelMode: google.maps.TravelMode.DRIVING
	}, options );

	if( settings.origin == null || settings.destination == null ) {
		// Return a distance of 0
		callback( settings.origin, settings.destination, 0 );
		return false;
	}

	// Compute the distance using the google service
	var service = new google.maps.DistanceMatrixService();
	var origins = [settings.origin];
	var destinations = [settings.destination];
	service.getDistanceMatrix(
		{
			origins: [settings.origin],
			destinations: [settings.destination],
			travelMode: settings.travelMode,
			unitSystem: settings.unitSystem,
			avoidHighways: false,
			avoidTolls: false
		}, 
		function(response, status ) {
			if (status != google.maps.DistanceMatrixStatus.OK) {
				alert('Error was: ' + status);
			} else {
				for (var i = 0; i < origins.length; i++) {
					var results = response.rows[i].elements;
					for (var j = 0; j < results.length; j++) {
						if( results[j].status != "ZERO_RESULTS" ) {
							callback( origins[i], destinations[j], results[j].distance.value );
						} else {
							callback( origins[i], destinations[j], null );
						}
					}
				}
			}
		}
	);
	
	return true;
}