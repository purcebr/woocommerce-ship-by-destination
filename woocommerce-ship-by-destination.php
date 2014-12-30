<?php
/**
 * @package woocommerce-ship-by-destination
 * @version 1.2
 */
/*woocommerce ship by destination
Plugin Name: WooCommerce Ship By Destination
Plugin URI: http://www.bryanpurcell.com
Description: Allows WC admin to limit shipping classes by Destination
Author: Bryan Purcell
Version: 1.2
Author URI: bryanpurcell.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Ship_By_Destination' ) ) :

class WC_Ship_By_Destination {
	
	public function __construct() {

		/* Actions */

		add_action( 'product_shipping_class_add_form_fields', array( $this, 'taxonomy_add_new_meta_field_callback' ), 10, 2 );
		add_action( 'product_shipping_class_edit_form', array( $this, 'taxonomy_edit_meta_field_callback' ) ,10,2);
		add_action( 'edited_product_shipping_class', array( $this, 'save_extra_fields_callback' ) , 10, 2);
		add_action( 'created_product_shipping_class', array( $this, 'save_extra_fields_callback' ), 10, 2);
		
		add_action( 'admin_enqueue_scripts', array( $this, 'ship_by_destination_scripts' ), 10 );
	
		/* Frontend Functionality */

		add_action('woocommerce_checkout_process',array( $this, 'wc_ship_destination_get_available_shipping_methods_process' ));
		add_filter('woocommerce_package_rates',array( $this, 'wc_ship_destination_get_available_shipping_methods_packages' ));
		add_filter('woocommerce_check_cart_items',array( $this, 'wc_ship_destination_get_available_shipping_methods_cart_update' ));
		add_filter('woocommerce_product_data_tabs', array($this, 'woocommerce_product_data_tabs_override'), 20);

		/* Localization */

		load_plugin_textdomain( 'woocommerce_ship_by_destination', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enquque the plugin scripts
	 *
	 * @access public
	 * @return void
	 */

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
		$countries = WC()->countries->countries;
		asort($countries);
		include('views/countries_list.php');   	
	}

	// Add term page
	public function taxonomy_add_new_meta_field_callback() {
		// this will add the custom meta field to the add new term page
		
		$countries = WC()->countries->countries;
		asort($countries);

		include('views/add_shipping_meta.php');
	}

	/**
	 * Edit Section for the shipping class terms
	 *
	 * @access public
	 * @return void
	 */

	public function taxonomy_edit_meta_field_callback($tag) {

		$term_id = $tag->term_id;
	    $term_data = get_option( "product_shipping_class_" . $term_id);
		// this will add the custom meta field to the add new term page

		if(isset($term_data['woocommerce_zones_shipping_countries'])) {
			$selections = $term_data['woocommerce_zones_shipping_countries'];
		} else {
			$selections = array();
		}

		if(isset($term_data['woocommerce_zones_availability']) && $term_data['woocommerce_zones_availability'] == 'all') {
			$default = ' selected="selected" ';
		} else {
			$default = '';
		}

		if(isset($term_data['woocommerce_zones_availability']) && $term_data['woocommerce_zones_availability'] == 'specific') {
			$custom = ' selected="selected" ';
		} else {
			$custom = '';
		}

		$countries = WC()->countries->countries;
		asort($countries);

		if($term_data['use_custom_error'] == 'default') {
			$has_custom_error = false;
		} else {
			$has_custom_error = true;
		}
		
		if($term_data['use_custom_notice'] == 'default') {
			$has_custom_notice = false;
		} else {
			$has_custom_notice = true;
		}

		include('views/edit_shipping_meta.php');
	}

	/**
	 * Save Term meta callback
	 *
	 * @access public
	 * @return void
	 */

	public function save_extra_fields_callback( $term_id ) {
		global $wpdb;

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
	    	$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_wc_ship_%')" );
	    }
	}

	/**
	 * Retrieve the notices, and add them if conditionally necessesary
	 *
	 * @access public
	 * @return void
	 */

	public function wc_ship_destination_get_available_shipping_methods_cart_update() {
		$notices = $this->wc_ship_destination_is_available();

		if( $this->has_shipping_quote_details() && WC()->cart->needs_shipping()) {
			foreach($notices['errors'] as $error) {
				if(!wc_has_notice($error, 'error')) {
					wc_add_notice($error, 'error');
				}
			}
		} else {
			foreach($notices['notices'] as $notice) {
				if(!wc_has_notice($notice, 'notice')) {
					wc_add_notice($notice, 'notice');
				}
			}
		}
	}

	/**
	 * Check to see if customer shipping details were entered, or packages are being returned because of a cached country or a default.
	 *
	 * @access public
	 * @return void
	 */

	public function has_shipping_quote_details() {
		if( ( WC()->customer->get_shipping_state() && WC()->customer->get_shipping_postcode() ) || WC()->customer->has_calculated_shipping() )  {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add errors to checkout page if we've gotten this far.
	 *
	 * @access public
	 * @return void
	 */

	public function wc_ship_destination_get_available_shipping_methods_process() {
		$notices = $this->wc_ship_destination_is_available();

		if(!empty($notices['errors'])) {
			foreach($notices['errors'] as $error) {
				wc_add_notice($error, 'error');
			}
		}
	}

	/**
	 * If there's an error, don't show any available methods on the cart page.
	 *
	 * @access public
	 * @return void
	 */

	public function wc_ship_destination_get_available_shipping_methods_packages($methods) {
		if(wc_notice_count( 'error' ) > 0) {
			return array();
		}
		else {
			return $methods;
		}
	}

	/**
	 * Look through the products and add any errors to an error array.
	 *
	 * @access public
	 * @return array Array of notices, both errors and warnings.
	 */

	public function wc_ship_destination_is_available() {

		/* Retrieve all the products in the cart */

		$cart_contents = WC()->cart->cart_contents;
		$products = array();

		foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
			$products[] = $values['product_id'];
		}

		/* Retrieve all the shipping classes for all the products in the cart */
		
		$shipping_classes = array();
		
		foreach($products as $p) {
			$prod = new WC_Product($p);
			$shipping_classes[] = $prod->get_shipping_class_id();	
		}

		$notices = $errors = array();

		if(isset(WC()->customer->country) && WC()->customer->country != '' ) {

			/* Starts with the Default country for a guest, or a pre-saved country for a signed-up user */

			$customer_country = WC()->customer->country;

			/* Loop through the shipping classes to figure out if any rules need apply to products in the cart */

			foreach(array_unique($shipping_classes) as $class) {
				$class_meta = get_option("product_shipping_class_" . $class);
			
				/* If there's a country rule in place... */

				if(isset($class_meta['woocommerce_zones_shipping_countries']) && is_array($class_meta['woocommerce_zones_shipping_countries'])) {
					
				/* Let's see if it matches */

					if(!in_array($customer_country, $class_meta['woocommerce_zones_shipping_countries']) && $class_meta['woocommerce_zones_availability'] == 'specific' && !empty($customer_country)) {

						/* It doesn't match one of the allowed countries, so let's load up the proper notice and error into an array of notices */

						if(isset($class_meta['use_custom_error']) && $class_meta['use_custom_error'] == 'custom' && isset($class_meta['custom_error']) && $class_meta['custom_error'] != '') {
							$errors[] = $class_meta['custom_error'];	
						} else {
							$errors[] = __("Product(s) in your cart are not available in the selected country.");
						}

						if(isset($class_meta['use_custom_notice']) && $class_meta['use_custom_notice'] == 'custom' && isset($class_meta['custom_notice']) && $class_meta['custom_notice'] != '') {
							$notices[] = $class_meta['custom_notice'];	
						} else {
							$notices[] = __("Product(s) in your cart not available select countries. Please make sure the products in your cart are available in your country.");
						}
					}
				}
				/* Onto the next shipping class */
			}
		}
		
		return array( 
			'notices' => $notices, 
			'errors' => $errors,
		);
	}

	/**
	 * Prevent the virtual product selection from hiding the shipping settings. We're using the shipping class for our Virtual Products Restrictions.
	 *
	 * @access public
	 * @return array The filter args, with the specific hide_if_virtual class unset.
	 */

	public function woocommerce_product_data_tabs_override($args) {
		if(($key = array_search('hide_if_virtual', $args['shipping']['class'])) !== false) {
		    unset($args['shipping']['class'][$key]);
		}
		return $args;
	}

}

global $WC_Ship_By_Destination;
$WC_Ship_By_Destination = new WC_Ship_By_Destination();

endif;

?>
