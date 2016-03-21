$(function() {
	$( '.add-more-info' ).on('click', function() {
		var url = $(this).attr('href');
		$( '#add-registration' )
			.attr('action', url)
			.attr('method', 'get')
			.submit();
		
		return false;
	})
	
});
