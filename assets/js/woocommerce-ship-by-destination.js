jQuery(window).load(function(){

	// Chosen selects
	jQuery("select.chosen_select").chosen();

	jQuery("select.chosen_select_nostd").chosen({
		allow_single_deselect: 'true'
	});

	var countrySettingsSelect = jQuery('select.method_availability');
	var countrySelectBox = jQuery('#shipping_countries_list');
	var useCustomErrorSettings = jQuery('#has_custom_error');
	var customError = jQuery('#custom_shipping_error');


	if(countrySettingsSelect.val() == 'all') {
		countrySelectBox.hide();
	}

	if(useCustomErrorSettings.val() == 'default') {
		customError.hide();
	}

	// Custom Error Settings

	jQuery('select.use_custom_error').change(function(){
		customError.toggle();
	});

	// Countries Select Settings

	countrySettingsSelect.change(function(){
		countrySelectBox.toggle();
	});


});





