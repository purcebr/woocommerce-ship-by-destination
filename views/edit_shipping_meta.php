<table class="ship_zones_country ship_zones_edit form-table">
	
	<tr>     
	
		<th scope="row" class="titledesc"><label for="woocommerce_zones_availability"><?php _e('Method availability','woocommerce_ship_by_destination'); ?></label></th>
		
		<td class="forminp">

			<fieldset>
				
				<legend class="screen-reader-text"><span><?php _e('Method availability','woocommerce_ship_by_destination'); ?></span></legend>
				
				<select name="term_meta[woocommerce_zones_availability]" id="woocommerce_zones_availability" style="" class="select method_availability">
					<option value="all" <?php echo $default; ?>><?php _e('All Countries Allowed','woocommerce_ship_by_destination'); ?></option>
					<option value="specific" <?php echo $custom; ?>><?php _e('Specific Countries','woocommerce_ship_by_destination'); ?></option>
				</select><br />
				
				<span class="description"><?php _e('Limit this shipping class to specific countries.','woocommerce_ship_by_destination'); ?></span>
			
			</fieldset>

		</td>

	</tr>

	<tr id="shipping_countries_list" class="form-field" valign="top">

		<th scope="row" class="titledesc"><label for="woocommerce_zones_shipping_countries"><?php _e('Selected Countries','woocommerce_ship_by_destination'); ?></label></th>
        
        <td class="forminp">
            
            <select multiple="multiple" name="woocommerce_zones_shipping_countries[]" data-placeholder="<?php _e('Choose countries&hellip;', 'woocommerce_ship_by_destination'); ?>" title="<?php _e('Country', 'woocommerce_ship_by_destination'); ?>" class="chosen_select">
	        	<?php
	        		if ($countries) foreach ($countries as $key=>$val) :
            			echo '<option value="'.$key.'" '.selected( in_array($key, $selections), true, false ).'>'.$val.'</option>';
            		endforeach;
            	?>
	        </select><br />

	        <span class="description"><?php _e('Countries to allow for this shipping class.','woocommerce_ship_by_destination'); ?></span>
   		
   		</td>

   	</tr>

	<tr class="form-field"> 

		<th scope="row" class="titledesc boxes_toggle"><label for="use_custom_notice"><?php _e('Use Custom Notice','woocommerce_ship_by_destination'); ?></label></th>
		
		<td class="forminp boxes_toggle">
		
			<fieldset>
				
				<legend class="screen-reader-text"><span><?php _e('Customize Notice Message','woocommerce_ship_by_destination'); ?></span></legend>
				
				<select  id="has_custom_notice" name="term_meta[use_custom_notice]" id="use_custom_notice" style="" class="select use_custom_notice">
					<option value="default" <?php echo (!$has_custom_notice) ? " selected='selected' " : ''; ?> ><?php _e('Default Notice','woocommerce_ship_by_destination'); ?></option>
					<option value="custom" <?php echo ($has_custom_notice) ? " selected='selected' " : ''; ?> ><?php _e('Add Custom Notice','woocommerce_ship_by_destination'); ?></option>
				</select><br />

				<span class="description"><?php _e('Use a custom notice message on the cart page, and/or the checkout page, indicating that that product may not be available for purchase in their country.','woocommerce_ship_by_destination'); ?></span>
			
			</fieldset>
		</td>

	</tr>

	<tr id="custom_shipping_notice" class="form-field">
		<th scope="row" class="titledesc boxes_toggle"><label for="woocommerce_free_shipping_countries"><?php _e('Custom Notice Message','woocommerce_ship_by_destination'); ?></label></th>
		<td class="forminp boxes_toggle">

			<fieldset>
			
				<legend class="screen-reader-text"><span><?php _e('Custom Notice Message','woocommerce_ship_by_destination'); ?></span></legend>
				
				<textarea name="term_meta[custom_notice]" class="custom_error"><?php echo (isset($term_data['custom_notice'])) ? $term_data['custom_notice'] : "";?></textarea><br />
				
				<span class="description"><?php _e('A custom notice on the cart page (and checkout page, for virtual products) indicating that the product might not be available for purchase in their country.','woocommerce_ship_by_destination'); ?></span>
			
			</fieldset>

		</td>

	</tr>

	<tr class="form-field">  

		<th scope="row" class="titledesc boxes_toggle"><label for="use_custom_error"><?php _e('Use Custom Error','woocommerce_ship_by_destination'); ?></label></th>

		<td class="forminp boxes_toggle">
			
			<fieldset>

				<legend class="screen-reader-text"><span><?php _e('Use Custom Error','woocommerce_ship_by_destination'); ?></span></legend>
				
				<select id="has_custom_error" name="term_meta[use_custom_error]" id="use_custom_error" style="" class="select use_custom_error">
					<option value="default" <?php echo (!$has_custom_error) ? " selected='selected' " : ''; ?> ><?php _e('Default Error','woocommerce_ship_by_destination'); ?></option>
					<option value="custom" <?php echo ($has_custom_error) ? " selected='selected' " : ''; ?> ><?php _e('Add Custom Error','woocommerce_ship_by_destination'); ?></option>
				</select><br />

				<span class="description"><?php _e('Use a custom error message when the customer cannot check out.','woocommerce_ship_by_destination'); ?></span>

			</fieldset>

		</td>

	</tr>

	<tr id="custom_shipping_error" class="form-field">

		<th scope="row" class="titledesc boxes_toggle"><label for="woocommerce_free_shipping_countries"><?php _e('Custom Error Message','woocommerce_ship_by_destination'); ?></label></th>

		<td class="forminp boxes_toggle">
			<fieldset>

				<legend class="screen-reader-text"><span><?php _e('Error Message','woocommerce_ship_by_destination'); ?></span></legend>
				
				<textarea name="term_meta[custom_error]" class="custom_error"><?php echo (isset($term_data['custom_error'])) ? $term_data['custom_error'] : "";?></textarea><br />
				
				<span class="description"><?php _e('A custom error to show when the rule fails, and the customer cannot buy the product.','woocommerce_ship_by_destination'); ?></span>

			</fieldset>
		</td>
	</tr>

</table>


	