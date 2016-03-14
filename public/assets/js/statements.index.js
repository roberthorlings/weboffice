$(function() {
	$('.date-range-input').webofficeDateRange(function(start,end,label) {
		var start_selector = this.element.data('selector-start');
		if(start_selector)
			$(start_selector).val(start.format('YYYY-MM-DD'));

		var end_selector = this.element.data('selector-end');
		if(end_selector)
			$(end_selector).val(end.format('YYYY-MM-DD'));
	});
	
	$( '#filter-statements' ).on('submit', function() {
		$('.date-range-input').remove();
		return true;
	});

});
