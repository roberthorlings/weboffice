$(function() {
	// Compute the sum of entered values
	$( '.invoice-line-amount, .invoice-line-number' ).on('change', function() {
		var sum = 0;
		$(this).parents('.invoice-details').find('.invoice-line').each(function(idx, tr) {
			var number = $(tr).find( '.invoice-line-number').val();
			var amount = $(tr).find( '.invoice-line-amount').val();
			
			if(number && amount) {
				sum += parseFloat(number) * parseFloat(amount);
			}
		});
		
		$('.invoice-line-total-amount').val(sum.toFixed(2));
	});
	
	// Handle date range pickers for project invoices
	$('.projects .date-range-input').each(function(idx,el) {
		$(el).webofficeDefaultDateRange();
	});	
});
