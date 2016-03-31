$(function() {
	/* Handle toggling the changed invoice address field */
	$( '.changed-invoice-address' ).on('click', function() {
		// Update default address, if requested
		updateDefaultInvoiceAddress();
	});
	
	/* Handle changes in address fields */
	$( '.address-field' ).on('change', updateDefaultInvoiceAddress);
	
	/* On load, make sure the state of the invoice address is set. 
	 * However, the value of the invoice address must not be changed
	 */
	$( '.invoice-address textarea' ).attr('readonly', !$( '.changed-invoice-address' ).is(':checked'));
	
	/**
	 * Updates the invoice address (if default is used)
	 */
	function updateInvoiceAddress(useDefault = true) {
		if( useDefault ) {
			$('#factuuradres').val(getDefaultInvoiceAddress());
		}
		$( '.invoice-address textarea' ).attr('readonly', useDefault);
	}
	
	function updateDefaultInvoiceAddress() {
		updateInvoiceAddress( !$( '.changed-invoice-address' ).is(':checked') );
	}

	/* Method to reset the invoice address */
	function getDefaultInvoiceAddress() {
		var contactpersoon = $( '#contactpersoon' ).val();

		var adres = $( '#bedrijfsnaam' ).val() + "\n" +
		( contactpersoon != "" ? "t.a.v. " + contactpersoon + "\n" : "" );

		// If a separate postal address is entered, use that one
		if( $( '#postadres' ).val() != "" ) {
			adres += $( '#postadres' ).val() + "\n" +
			$( '#postpostcode' ).val() + " " + $( '#postplaats' ).val();
		} else {
			adres += $( '#adres' ).val() + "\n" +
			$( '#postcode' ).val() + " " + $( '#plaats' ).val();
		}

		return adres
	}		
});
