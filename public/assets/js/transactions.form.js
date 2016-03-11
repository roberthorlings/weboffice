$(function() {
	// Mark the total as balanced or not-balanced
	function checkBalance() {
		var original = Math.abs(parseFloat($('#bedrag').val()));
		var total = Math.abs(parseFloat($('.statement-total-amount').val()));
		var isBalanced = Math.abs(original - total) < 0.005;
		
		console.log(original, total, isBalanced);
		
		if(isBalanced) {
			$('.statement-total-amount').removeClass('not-balanced');
		} else {
			$('.statement-total-amount').addClass('not-balanced');
		}
	}

	// Compute the sum of entered values
	$( '.statement-amount' ).on('change', function() {
		var sum = 0;
		
		$('.statement-amount').each(function(idx, el) {
			if($(el).val())
				sum += parseFloat($(el).val());
		});
		
		$('.statement-total-amount').val(sum.toFixed(2));
		checkBalance();
	});
	
	// Check balance at start
	checkBalance();
});
