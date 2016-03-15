$(function() {
	// Mark the total as balanced or not-balanced
	function checkBalance() {
		var total = Math.abs(parseFloat($('.statement-total-amount').val()));
		var isBalanced = total < 0.005;
		
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
			if($(el).val()) {
				var sign = $(el).parents('.transaction-line').find( '.statement-sign' ).val() == '1' ? 1.0 : -1.0;
				sum += parseFloat($(el).val()) * sign;
				console.log($(el).parents('.transaction-line').find( '.statement-sign' ), sign, $(el).val());
			}
		});
		
		$('.statement-total-amount').val(sum.toFixed(2));
		checkBalance();
	});
	
	// Check balance at start
	checkBalance();
});
