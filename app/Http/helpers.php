<?php
	/**
	 * Returns a timespan object for the given start and end
	 */
	function toTimespan($start, $end) {
		return new Weboffice\Support\Timespan($start, $end);
	}