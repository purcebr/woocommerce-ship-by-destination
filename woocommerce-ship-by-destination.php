<?php
/**
 * @package woocommerce-ship-by-destination
 * @version 1.0.1
 */
/*woocommerce ship by destination
Plugin Name: WooCommerce Ship By Destination
Plugin URI: http://aveight.com
Description: Allows WC admin to limit shipping classes by Destination
Author: avEIGHT, Inc.
Version: 1.0.1
Author URI: bryan@aveight.com
*/
?>
<?php
global $woocommerce;

add_action( 'product_shipping_class_add_form_fields', 'av8_taxonomy_add_new_meta_field', 10, 2 );
add_action('product_shipping_class_edit_form', 'av8_taxonomy_edit_meta_field',10,2);
add_action( 'edited_product_shipping_class', 'av8_save_extra_fields_callback', 10, 2);
add_action( 'created_product_shipping_class', 'av8_save_extra_fields_callback', 10, 2);


wp_register_script( 'chosen-wc-ship-by-destination', av8_plugin_url() . '/assets/js/chosen/chosen.jquery.js?ver=3.4.2' );
wp_register_script( 'chosenmin-wc-ship-by-destination', av8_plugin_url() . '/assets/js/chosen/chosen.jquery.min.js?ver=1.6.5.2' );
wp_register_script( 'chosenajax-wc-ship-by-destination', av8_plugin_url() . '/assets/js/chosen/ajax-chosen.jquery.min.js?ver=1.6.5.2' );
wp_register_script( 'wc-ship-by-destination-js', av8_plugin_url() . '/assets/js/woocommerce-ship-by-destination.js' );

wp_enqueue_script( 'chosen-wc-ship-by-destination' );
wp_enqueue_script( 'wc-ship-by-destination-js' );
wp_enqueue_script( 'chosenmin-wc-ship-by-destination' );
wp_enqueue_script( 'chosenajax-wc-ship-by-destination' );

//Add styles

function av8_ship_destination_css() {
	wp_register_style( 'wc-ship-destination', av8_plugin_url(). '/assets/css/style.css', '', '1.0.0', 'screen' );
	wp_enqueue_style( 'wc-ship-destination' ); 
}

add_action( 'admin_enqueue_scripts',  'av8_ship_destination_css', 10 );


/**
 * Get the plugin url.
 *
 * @access public
 * @return string
 */
function av8_plugin_url() {
	return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
}
/**
 * Print countries for shipping classes
 *
 * @access public
 * @return void
 */
function av8_print_countries($value, $selections = array()){
global $woocommerce;

$countries = $woocommerce->countries->countries;
            	asort($countries);
            	?><tr valign="top">
					<th scope="row" class="titledesc">
						<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
					</th>
                    <td class="forminp">
	                    <select multiple="multiple" name="<?php echo esc_attr( $value['id'] ); ?>[]" style="width:450px;" data-placeholder="<?php _e('Choose countries&hellip;', 'woocommerce'); ?>" title="Country" class="chosen_select">
				        	<?php
				        		if ($countries) foreach ($countries as $key=>$val) :
	                    			echo '<option value="'.$key.'" '.selected( in_array($key, $selections), true, false ).'>'.$val.'</option>';
	                    		endforeach;
	                    	?>
				        </select>
               		</td>
               	</tr><?php
               	
               	
               	}

// Add term page
function av8_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
<table class="ship_zones_country">
   <tr>     <th scope="row" class="titledesc"><label for="woocommerce_zones_availability">Method availability</label></th>
<td class="forminp">
	<fieldset><legend class="screen-reader-text"><span>Method availability</span></legend>
	<select name="term_meta[woocommerce_zones_availability]" id="woocommerce_zones_availability" style="" class="select method_availability"><option value="all"  selected='selected'>All allowed countries</option><option value="specific" >Specific Countries</option></select></fieldset>
</td>
</tr>
<?php av8_print_countries(array('id'=>'woocommerce_zones_shipping_countries', 'name'=>'Specific Countries')); ?>

  <tr>     <th scope="row" class="titledesc"><label for="woocommerce_zones_availability">Use Custom Error</label></th>
<td class="forminp">
	<fieldset><legend class="screen-reader-text"><span>Custom Error</span></legend>
	<select name="term_meta[use_custom_error]" id="use_custom_error" style="" class="select use_custom_error"><option value="all"  selected='selected'>Default Error</option><option value="custom" >Add Custom Error</option></select></fieldset>
</td>
</tr><tr>
<th scope="row" class="titledesc"><label for="custom_error">Error Message</label></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span>Error Message</span></legend>
<input type="text" name="term_meta[custom_error]" class="custom_error" value="<?php ?>">
</td></tr>


</table>
<?php
}


// Add term page
function av8_taxonomy_edit_meta_field($tag) {

$t_id = $tag->term_id;
    $term_data = get_option( "product_shipping_class_" . $t_id);
	// this will add the custom meta field to the add new term page
	?>
	
		
	<?php 
	
	if($term_data['woocommerce_zones_availability'] == 'all')
		$default = ' selected="selected" ';
	else
		$default = '';
		
	if($term_data['woocommerce_zones_availability'] == 'specific')
		$custom = ' selected="selected" ';
	else
		$custom = '';
	
	
	?>
	
	
<table class="ship_zones_country ship_zones_edit">
   <tr>     <th scope="row" class="titledesc"><label for="woocommerce_zones_availability">Method availability</label></th>
<td class="forminp">
	<fieldset><legend class="screen-reader-text"><span>Method availability</span></legend>
	<select name="term_meta[woocommerce_zones_availability]" id="woocommerce_zones_availability" style="" class="select method_availability"><option value="all" <?php echo $default; ?>>All allowed countries</option><option value="specific" <?php echo $custom; ?>>Specific Countries</option></select></fieldset>
</td>
</tr>
<?php $selections = $term_data['woocommerce_zones_shipping_countries']; ?>

<?php av8_print_countries(array('id'=>'woocommerce_zones_shipping_countries', 'name'=>'Specific Countries'), $selections); ?>

  <tr>     <th scope="row" class="titledesc"><label for="use_custom_error">Use Custom Error</label></th>
<td class="forminp">
	<fieldset><legend class="screen-reader-text"><span>Custom Error</span></legend>
	
	<?php 
	if($term_data['use_custom_error'] == 'all')
		$default = ' selected="selected" ';
	else
		$default = '';
		
	if($term_data['use_custom_error'] == 'custom')
		$custom = ' selected="selected" ';
	else
		$custom = '';
	?>
	
<select name="term_meta[use_custom_error]" id="use_custom_error" style="" class="select use_custom_error"><option value="all"  <?php echo $default; ?> >Default Error</option><option value="custom" <?php echo $custom; ?> >Add Custom Error</option></select></fieldset>
</td>
</tr><tr>
<th scope="row" class="titledesc"><label for="woocommerce_free_shipping_countries">Specific Error Message</label></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span>Error Message</span></legend>
<input type="text" name="term_meta[custom_error]" class="custom_error" value="<?php echo $term_data['custom_error'];?>">
</td></tr>


</table>
<?php
}


function av8_save_extra_fields_callback( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "product_shipping_class_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['term_meta'][$key])){
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        if(isset( $_POST['woocommerce_zones_shipping_countries'] ) ) {
        	$term_meta['woocommerce_zones_shipping_countries'] =$_POST['woocommerce_zones_shipping_countries'];
        }
        //save the option array
        update_option( "product_shipping_class_$t_id", $term_meta );
    }
}

add_filter('woocommerce_available_shipping_methods','wc_ship_destination_get_available_shipping_methods',0);

function wc_ship_destination_get_available_shipping_methods($methods) {

global $woocommerce;
$cart_contents = $cart->cart_contents;

	foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
		$products[] = $values['product_id'];
	}
	
	$shipping_classes = array();
	foreach($products as $p):
		$prod = new WC_Product($p);
		$shipping_classes[] = $prod->get_shipping_class_id();
		
	endforeach;

	$shipping_obj = $woocommerce->shipping;
	
	$country = '';
	foreach($shipping_obj->packages as $p):
		$country = $p['destination']['country'];
	endforeach;

	$pass = true;
	foreach(array_unique($shipping_classes) as $class):
		//Check class for location settings...
		
		//Get the meta
		
		$class_meta = get_option("product_shipping_class_" . $class);
	
		if(isset($class_meta['woocommerce_zones_shipping_countries']) && is_array($class_meta['woocommerce_zones_shipping_countries'])) {
			if(!in_array($country, $class_meta['woocommerce_zones_shipping_countries']) && $class_meta['woocommerce_zones_availability'] == 'specific' && !empty($country))
			{
				$pass = false;
				if($class_meta['use_custom_error'] == 'custom')
				{
					$woocommerce->add_error($class_meta['custom_error']);
				}
			}
		}
	endforeach;

	if(!$pass)
	{
$woocommerce->messages = array();

				return '';
	}
	else	
	{
		//$woocommerce->clear_messages();
		return $methods;
	}
}

?>