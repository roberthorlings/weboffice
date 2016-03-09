$(function() {
	$('.date-range-input').webofficeDateRange(function(start,end,label) {
		var start_selector = this.element.data('selector-start');
		if(start_selector)
			$(start_selector).val(start.format('YYYY-MM-DD'));

		var end_selector = this.element.data('selector-end');
		if(end_selector)
			$(end_selector).val(end.format('YYYY-MM-DD'));
	});
	
	$( '#filter-workinghours' ).on('submit', function() {
		$('.date-range-input').remove();
		return true;
	});
	
	$( '.add-more-info' ).on('click', function() {
		var url = $(this).attr('href');
		$( '#add-registration' )
			.attr('action', url)
			.attr('method', 'get')
			.submit();
		
		return false;
	})
	
});
