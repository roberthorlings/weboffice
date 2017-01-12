<?php
/**
 * Returns a timespan object for the given start and end
 */
function toTimespan($start, $end) {
	return new Weboffice\Support\Timespan ( $start, $end );
}

/**
 * Returns a filter array which is safe to be used for pagination
 *
 * It effectively filters dates and converts them to yyyy-mm-dd format
 * 
 * @param array $filter        	
 */
function paginationSafeFilter($filter) {
	$output = [ ];
	
	foreach ( $filter as $key => $value ) {
		if ($value instanceof Carbon\Carbon) {
			$value = $value->format ( 'Y-m-d' );
		}
		
		$output [$key] = $value;
	}
	
	return $output;
}