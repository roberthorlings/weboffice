$(function() {
    // Add the link title to the title bar
    $('#daterange').text(dateRangeConfig.linkTitle);
    
    // Default ranges
	var ranges = {
		'Vorig jaar': [moment().subtract(1, 'year' ).startOf('year'), moment().subtract(1, 'year' ).endOf('year')],
		'Dit jaar': [moment().startOf('year'), moment().endOf('year')],
		'Vorige maand': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Deze maand': [moment().startOf('month'), moment().endOf('month')],
		'Alles': [dateRangeConfig.firstDate, moment()]
	};
	
	// Defaults for the daterangepicker
	var defaults = {
        ranges: ranges,
        opens: 'left',
        format: 'DD-MM-YYYY',
        startDate: dateRangeConfig.startDate,
        endDate: dateRangeConfig.endDate,
        minDate: dateRangeConfig.firstDate,
    };

	// Add jquery method to enable reuse
	$.fn.webofficeDateRange = function(callback, options) {
		options = $.extend({}, defaults, options);
		
	    this.daterangepicker(options, callback);
	};
	
	// Add jquery method to initialize a default daterangepicker
	$.fn.webofficeDefaultDateRange = function(options) {
		// Retrieve selected start and end date from label
		var val = $(this).val();
		var currentDefaults = {};
		
		if(val) {
			parts = val.split(' - ');
			currentDefaults.startDate = parts[0];
			currentDefaults.endDate = parts[1];
		}

		options = $.extend({}, currentDefaults, options);
		
		$(this).webofficeDateRange(function(start,end,label) {
			var start_selector = this.element.data('selector-start');
			if(start_selector)
				$(start_selector).val(start.format('YYYY-MM-DD'));
		
			var end_selector = this.element.data('selector-end');
			if(end_selector)
				$(end_selector).val(end.format('YYYY-MM-DD'));
		}, options);
	};	
	
	// Initialize global date range picker
	$('.date-range-button').webofficeDateRange(function (start, end, label) {
        // send post.
        $.post(dateRangeConfig.URL, {
            start: start.format('YYYY-MM-DD'),
            end: end.format('YYYY-MM-DD'),
            label: label,
            _token: token
        }).done(function () {
            console.log('Succesfully sent new date range.');
            window.location.reload(true);
        }).fail(function () {
            console.log('Could not send new date range.');
            alert('Could not change date range');

        });
    });
});