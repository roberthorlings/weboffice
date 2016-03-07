$(function() {
	var ranges = {
		'Vorig jaar': [moment().subtract(1, 'year' ).startOf('year'), moment().subtract(1, 'year' ).endOf('year')],
		'Dit jaar': [moment().startOf('year'), moment().endOf('year')],
		'Vorige maand': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Deze maand': [moment().startOf('month'), moment().endOf('month')],
		'Alles': [dateRangeConfig.firstDate, moment()]
	};
	
    // build the data range:
    $('#daterange').text(dateRangeConfig.linkTitle);
    $('.date-range-button').daterangepicker(
        {
            ranges: ranges,
            opens: 'left',
            format: 'YYYY-MM-DD',
            startDate: dateRangeConfig.startDate,
            endDate: dateRangeConfig.endDate
        },
        function (start, end, label) {
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
        }
    );
});