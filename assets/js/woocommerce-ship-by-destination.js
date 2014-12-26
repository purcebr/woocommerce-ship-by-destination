				jQuery(window).load(function(){
					// Subsubsub tabs
					jQuery('div.subsubsub_section ul.subsubsub li a:eq(0)').addClass('current');
					jQuery('div.subsubsub_section .section:gt(0)').hide();
					jQuery('select.subsubsub_section .section:gt(0)').hide();


 jQuery('select.method_availability').change(function(){
		if (jQuery(this).val()=="specific") {
			jQuery(this).closest('tr').next('tr').show();
		} else {
			jQuery(this).closest('tr').next('tr').hide();
		}
						
	}).change();

 jQuery('select.use_custom_error').change(function(){
		if (jQuery(this).val()=="custom") {
			jQuery(this).closest('tr').next('tr').show();
		} else {
			jQuery(this).closest('tr').next('tr').hide();
		}
						
	}).change();

					jQuery('div.subsubsub_section ul.subsubsub li a').click(function(){
						var $clicked = jQuery(this);
						var $section = $clicked.closest('.subsubsub_section');
						var $target  = $clicked.attr('href');

						$section.find('a').removeClass('current');

						if ( $section.find('.section:visible').size() > 0 ) {
							$section.find('.section:visible').fadeOut( 100, function() {
								$section.find( $target ).fadeIn('fast');
							});
						} else {
							$section.find( $target ).fadeIn('fast');
						}

						$clicked.addClass('current');
						jQuery('#last_tab').val( $target );

						return false;
					});

					
					// Countries
					jQuery('select#woocommerce_allowed_countries').change(function(){
						if (jQuery(this).val()=="specific") {
							jQuery(this).parent().parent().next('tr').show();
						} else {
							jQuery(this).parent().parent().next('tr').hide();
						}
					}).change();

					// Color picker
					jQuery('.colorpick').each(function(){
						jQuery('.colorpickdiv', jQuery(this).parent()).farbtastic(this);
						jQuery(this).click(function() {
							if ( jQuery(this).val() == "" ) jQuery(this).val('#');
							jQuery('.colorpickdiv', jQuery(this).parent() ).show();
						});
					});
					jQuery(document).mousedown(function(){
						jQuery('.colorpickdiv').hide();
					});

					// Edit prompt
					jQuery(function(){
						var changed = false;

						jQuery('input, textarea, select, checkbox').change(function(){
							changed = true;
						});

						jQuery('.woo-nav-tab-wrapper a').click(function(){
							if (changed) {
								window.onbeforeunload = function() {
								    return 'The changes you made will be lost if you navigate away from this page.';
								}
							} else {
								window.onbeforeunload = '';
							}
						});

						jQuery('.submit input').click(function(){
							window.onbeforeunload = '';
						});
					});

					// Sorting
					

					// Chosen selects
					jQuery("select.chosen_select").chosen();

					jQuery("select.chosen_select_nostd").chosen({
						allow_single_deselect: 'true'
					});
				});
				
				
				  
				  
				   
