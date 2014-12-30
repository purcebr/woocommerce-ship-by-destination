jQuery(window).load(function(){

	// Chosen selects
	jQuery("select.chosen_select").chosen();

	jQuery("select.chosen_select_nostd").chosen({
		allow_single_deselect: 'true'
	});

	var countrySettingsSelect = jQuery('select.method_availability');
	var countrySelectBox = jQuery('#shipping_countries_list');
	var useCustomErrorSettings = jQuery('#has_custom_error');
	var useCustomNoticeSettings = jQuery('#has_custom_notice');
	var customError = jQuery('#custom_shipping_error');
	var customNotice = jQuery('#custom_shipping_notice');
	var customBoxes = jQuery('.boxes_toggle');

	if(useCustomErrorSettings.val() == 'default') {
		customError.hide();
	}

	if(useCustomNoticeSettings.val() == 'default') {
		customNotice.hide();
	}

	if(countrySettingsSelect.val() == 'all') {
		countrySelectBox.hide();
		customBoxes.hide();
	}

	// Custom Error Settings

	jQuery('select.use_custom_error').change(function(){
		customError.toggle();
	});

	// Custom Error Settings

	jQuery('select.use_custom_notice').change(function(){
		customNotice.toggle();
	});

	// Countries Select Settings

	countrySettingsSelect.change(function(){
		countrySelectBox.toggle();
		customBoxes.toggle();

	});


});





