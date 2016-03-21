$(function() {
	// Handle date ranges in filter
	$('.filter .date-range-input').each(function(idx,el) {
		// Retrieve selected start and end date from label
		var val = $(el).val();
		var options = {};
		
		if(val) {
			parts = val.split(' - ');
			options.startDate = parts[0];
			options.endDate = parts[1];
		}
		
		$(el).webofficeDateRange(function(start,end,label) {
			var start_selector = this.element.data('selector-start');
			if(start_selector)
				$(start_selector).val(start.format('YYYY-MM-DD'));
		
			var end_selector = this.element.data('selector-end');
			if(end_selector)
				$(end_selector).val(end.format('YYYY-MM-DD'));
		}, options);
	});
	
	// Handle single date input
	$('.filter .single-date-input').each(function(idx,el) {
		$(el).webofficeDateRange(function(start,end,label) {
			var date_selector = this.element.data('selector-date');
			if(date_selector)
				$(date_selector).val(start.format('YYYY-MM-DD'));
		}, { singleDatePicker: true, startDate: $(el).val() });
	});
	
	// Make sure the date range and single date input fields (with human readable text) is not submitted
	$( 'form.filter' ).on('submit', function() {
		$('.date-range-input').remove();
		$('.single-date-input').remove();
		return true;
	});
	
});