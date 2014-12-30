<div id="use_custom_shipping_error" class="form-field">
	<label for="custom_error"><?php _e('Method availability','woocommerce_ship_by_destination'); ?></label>

	<select name="term_meta[woocommerce_zones_availability]" id="woocommerce_zones_availability" style="" class="select method_availability">
		<option value="all" <?php echo (!isset($term_data['woocommerce_zones_availability']) || $term_data['woocommerce_zones_availability'] == 'all') ? " selected='selected' " : ''; ?>><?php _e('All Allowed Countries','woocommerce_ship_by_destination'); ?></option>
		<option value="specific" <?php echo (isset($term_data['woocommerce_zones_availability']) && $has_custom_countries == 'specific') ? " selected='selected' " : ''; ?> > <?php _e('Specific Countries','woocommerce_ship_by_destination'); ?></option>
	</select>

	<p><?php _e('Limit this shipping class to specific countries.','woocommerce_ship_by_destination'); ?></p>
</div>

<div id="shipping_countries_list" class="form-field">
  <label for="custom_error"><?php _e('Use Custom Error','woocommerce_ship_by_destination'); ?></label>
  <select multiple="multiple" name="woocommerce_zones_shipping_countries[]" data-placeholder="<?php _e('Choose countries&hellip;', 'woocommerce_ship_by_destination'); ?>" title="<?php _e('Country', 'woocommerce_ship_by_destination'); ?>" class="chosen_select">
    <?php
    if ($countries) foreach ($countries as $key=>$val) :
      echo '<option value="'.$key.'">'.$val.'</option>';
    endforeach;
    ?>
  </select>
  <p><?php _e('Countries to allow for this shipping class.','woocommerce_ship_by_destination'); ?></p>
</div>

<div id="use_custom_shipping_notice" class="form-field">
	<label for="custom_notice"><?php _e('Customize Notice Message?','woocommerce_ship_by_destination'); ?></label>
	<select  id="has_custom_notice" name="term_meta[use_custom_notice]" id="use_custom_notice" style="" class="select use_custom_notice">
		<option value="default" <?php echo (!isset($term_data['has_custom_notice']) || $term_data['has_custom_notice'] == 'default') ? " selected='selected' " : ''; ?> ><?php _e('Default Notice','woocommerce_ship_by_destination'); ?></option>
		<option value="custom" <?php echo (isset($term_data['has_custom_notice']) && $term_data['has_custom_notice'] == 'custom') ? " selected='selected' " : ''; ?> ><?php _e('Add Custom Notice','woocommerce_ship_by_destination'); ?></option>
	</select>

	<p><?php _e('Use a custom notice message on the cart page, and/or the checkout page, indicating that that product may not be available for purchase in their country.','woocommerce_ship_by_destination'); ?></p>
</div>

<div id="custom_shipping_notice" class="form-field">
	<label for="custom_notice"><?php _e('Custom Notice Message','woocommerce_ship_by_destination'); ?></label>
	<textarea name="term_meta[custom_notice]" rows="5" cols="40"><?php echo (isset($term_data['custom_notice'])) ? $custom_notice : ""; ?></textarea>
	<p><?php _e('A Custom Error to show when the rule fails, and the customer cannot buy the product.','woocommerce_ship_by_destination'); ?></p>
</div>

<div id="use_custom_shipping_error" class="form-field">
	<label for="custom_error"><?php _e('Customize Error Message?','woocommerce_ship_by_destination'); ?></label>
	<select  id="has_custom_error" name="term_meta[use_custom_error]" id="use_custom_error" style="" class="select use_custom_error">
		<option value="default" <?php echo (!isset($term_data['has_custom_error']) || $term_data['has_custom_error'] == 'default') ? " selected='selected' " : ''; ?> ><?php _e('Default Error','woocommerce_ship_by_destination'); ?></option>
		<option value="custom" <?php echo (isset($term_data['has_custom_error']) && $term_data['has_custom_error'] == 'custom') ? " selected='selected' " : ''; ?> ><?php _e('Add Custom Error','woocommerce_ship_by_destination'); ?></option>
	</select>
	<p><?php _e('Use a custom error message when the customer cannot check out.','woocommerce_ship_by_destination'); ?></p>
</div>

<div id="custom_shipping_error" class="form-field">
	<label for="custom_error"><?php _e('Custom Error Message','woocommerce_ship_by_destination'); ?></label>
	<textarea name="term_meta[custom_error]" rows="5" cols="40"><?php echo (isset($term_data['custom_error'])) ? $custom_error : ""; ?></textarea>
	<p><?php _e('A Custom Error to show when the rule fails, and the customer cannot buy the product.','woocommerce_ship_by_destination'); ?></p>
</div>
