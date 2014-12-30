jQuery(document).ready(function(){
	jQuery('#billing_country, #shipping_country').on('change', function() {
		var ship_notices = jQuery('.woocommerce-info--wc-ship-destination, .woocommerce-error--wc-ship-destination');

		if(allowedCountries !== 'undefined' && jQuery.inArray(this.value, allowedCountries) > -1) {
			ship_notices.hide();
		} else {
			ship_notices.show();
		}
	});
});