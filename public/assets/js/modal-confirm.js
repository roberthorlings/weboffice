$(function() {
	function getModal(text) {
		if (!$('#dataConfirmModal').length) {
			$('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">' +
					'<div class="modal-header">' +
						'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>' +
						'<h4 id="dataConfirmLabel" class="modal-title">Please Confirm</h3>' +
					'</div>' +
					'<div class="modal-body"></div>' +
					'<div class="modal-footer"><a class="btn btn-primary" id="dataConfirmOK">OK</a><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button></div>' +
					'</div></div></div>');
		} 
		
		var modal = $('#dataConfirmModal');
		
		if(text)
			modal.find('.modal-body').text(text);
		
		return modal;
	}
	
	$('a[data-confirm]').click(function(ev) {
		var href = $(this).attr('href');
		
		var modal = getModal($(this).attr('data-confirm'))
		$('#dataConfirmOK').attr('href', href);
		
		modal.modal({show:true});
		
		return false;
	});
	
	// Handle form submission with data confirm
	$('form[data-confirm]').submit(function(ev) {
		var form = $(this);
		var modal = getModal(form.attr('data-confirm'));
		
		$('#dataConfirmOK').on('click', function() {
			// Remove submit handler 
			form.off('submit');
			form.submit(); 
		});
		
		modal.modal({show:true});
		return false;
	});
	
	
	
});