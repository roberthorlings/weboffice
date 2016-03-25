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
	
	function handleProjectInvoiceSelection() {
		// Loop through the projects
		var projectSelects = $('.project-invoice-select');
		var invoiceLines = $('.invoice-line');
		
		projectSelects.each(function(idx,el) {
			var correspondingLine = invoiceLines.eq(idx);
			
			// If a project is selected, put its name into the title box
			// and 
			if( $(el).val() ) {
				correspondingLine.find( '.invoice-line-description' ).val( $(el).find( 'option:selected' ).text().trim() );
				correspondingLine.find( '.invoice-line-description, .invoice-line-number, .invoice-line-amount' ).attr( 'readonly', true);
				correspondingLine.find( '.post-select' ).select2("enable", false);
			} else {
				correspondingLine.find( '.invoice-line-description, .invoice-line-number, .invoice-line-amount' ).attr( 'readonly', false);
				correspondingLine.find( '.post-select' ).select2("enable", true);
			}
		});
	}
	
	// Project invoices. If a project is selected, disable the first 
	// row 
	$('.project-invoice-select').on('change', function() {
		handleProjectInvoiceSelection();
	});
	
	handleProjectInvoiceSelection();
});
