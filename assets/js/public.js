jQuery(document).ready(function(){
	jQuery('#billing_country').on('change', function() {
		var ship_notices = jQuery('.woocommerce-info--wc-ship-destination, .woocommerce-error');

		if(allowedCountries !== 'undefined' && jQuery.inArray(this.value, allowedCountries) > -1) {
			ship_notices.hide();
		} else {
			ship_notices.show();
		}
	});
});