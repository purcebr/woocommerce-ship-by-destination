<?php
/**
 * @package woocommerce-ship-by-destination
 * @version 1.1
 */
/*woocommerce ship by destination
Plugin Name: WooCommerce Ship By Destination
Plugin URI: http://aveight.com
Description: Allows WC admin to limit shipping classes by Destination
Author: Bryan Purcell
Version: 1.1
Author URI: bryanpurcell.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Ship_By_Destination' ) ) :

class WC_Ship_By_Destination {
	
	public function __construct() {

		global $woocommerce;

		/* Actions */

		add_action( 'product_shipping_class_add_form_fields', array( $this, 'taxonomy_add_new_meta_field_callback' ), 10, 2 );
		add_action( 'product_shipping_class_edit_form', array( $this, 'taxonomy_edit_meta_field_callback' ) ,10,2);
		add_action( 'edited_product_shipping_class', array( $this, 'save_extra_fields_callback' ) , 10, 2);
		add_action( 'created_product_shipping_class', array( $this, 'save_extra_fields_callback' ), 10, 2);
		
		add_action( 'admin_enqueue_scripts', array( $this, 'ship_by_destination_scripts' ), 10 );
	
		/* Filters */	

		add_filter('woocommerce_package_rates',array( $this, 'wc_ship_destination_get_available_shipping_methods' ));

		/* Localization */

		load_plugin_textdomain( 'woocommerce_ship_by_destination', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	public function ship_by_destination_scripts() {

		/* Register Scripts */
		$screen = get_current_screen();

		if(!$screen->base == 'edit-tags' || !$screen->taxonomy == 'product_shipping_class')
			return;

		wp_register_script( 'chosenmin-wc-ship-by-destination', $this->get_plugin_url() . '/assets/js/chosen/chosen.jquery.min.js?ver=1.6.5.2' );
		wp_register_script( 'wc-ship-by-destination-js', $this->get_plugin_url() . '/assets/js/woocommerce-ship-by-destination.js' );
		wp_register_style( 'wc-ship-destination', $this->get_plugin_url(). '/assets/css/style.css', '', '1.0.0', 'screen' );

		/* Enqueue the Scripts */
		wp_enqueue_script( 'chosenmin-wc-ship-by-destination' );
		wp_enqueue_script( 'wc-ship-by-destination-js');
		wp_enqueue_style( 'wc-ship-destination' ); 

	}


	/**
	 * Get the plugin url.
	 *
	 * @access public
	 * @return string
	 */

	public function get_plugin_url() {
		return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
	}

	/**
	 * Print countries for shipping classes
	 *
	 * @access public
	 * @return void
	 */

	public function print_countries($value, $selections = array()){
		global $woocommerce;
		$countries = $woocommerce->countries->countries;
		asort($countries);
		include('views/countries_list.php');   	
	}

	// Add term page
	public function taxonomy_add_new_meta_field_callback() {
		// this will add the custom meta field to the add new term page
		
		global $woocommerce;
		$countries = $woocommerce->countries->countries;
		asort($countries);

		include('views/add_shipping_meta.php');
	}

	// Edit term page
	public function taxonomy_edit_meta_field_callback($tag) {

		$term_id = $tag->term_id;
	    $term_data = get_option( "product_shipping_class_" . $term_id);
		// this will add the custom meta field to the add new term page

		if(isset($term_data['woocommerce_zones_shipping_countries'])) {
			$selections = $term_data['woocommerce_zones_shipping_countries'];
		} else {
			$selections = array();
		}

		if(isset($term_data['woocommerce_zones_availability']) && $term_data['woocommerce_zones_availability'] == 'all')
			$default = ' selected="selected" ';
		else
			$default = '';

		if(isset($term_data['woocommerce_zones_availability']) && $term_data['woocommerce_zones_availability'] == 'specific')
			$custom = ' selected="selected" ';
		else
			$custom = '';

		global $woocommerce;
		$countries = $woocommerce->countries->countries;
		asort($countries);

		if($term_data['use_custom_error'] == 'default')
			$has_custom_error = false;
		else
			$has_custom_error = true;
		
		include('views/edit_shipping_meta.php');
	}


	public function save_extra_fields_callback( $term_id ) {
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

	        //Clear rate cache
	        global $wpdb;
	    	$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_wc_ship_%')" );
	    }
	}

	public function wc_ship_destination_get_available_shipping_methods($methods) {

		global $woocommerce;
		$cart_contents = $woocommerce->cart->cart_contents;

		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
			$products[] = $values['product_id'];
		}
		
		$shipping_classes = array();
		foreach($products as $p):
			$prod = new WC_Product($p);
			$shipping_classes[] = $prod->get_shipping_class_id();
			
		endforeach;

		$customer_country = $woocommerce->customer->shipping_country;

		$pass = true;

		foreach(array_unique($shipping_classes) as $class) {
			$class_meta = get_option("product_shipping_class_" . $class);
		
			if(isset($class_meta['woocommerce_zones_shipping_countries']) && is_array($class_meta['woocommerce_zones_shipping_countries'])) {
				if(!in_array($customer_country, $class_meta['woocommerce_zones_shipping_countries']) && $class_meta['woocommerce_zones_availability'] == 'specific' && !empty($customer_country))
				{
					$pass = false;
					if($class_meta['use_custom_error'] == 'custom')
						wc_add_notice($class_meta['custom_error'], 'error');
				}
			}
		}

		if(!$pass)
			return array(); //No Shipping Allowed
		else	
			return $methods; //Shipping is Allowed
	}
}

global $WC_Ship_By_Destination;

$WC_Ship_By_Destination = new WC_Ship_By_Destination();

endif;

?>
