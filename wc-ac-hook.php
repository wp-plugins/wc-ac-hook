<?php
/**
 * Plugin Name: WC-AC Hook
 * Plugin URI:
 * Description: Integrates WooCommerce with ActiveCampaign by adding or updating a contact on ActiveCampaign with specified tags, when an order is completed on WooCommerce
 * Version: 1.0
 * Author: Matthew Treherne
 * Author URI: https://profiles.wordpress.org/mtreherne
 * Text Domain: wc-ac-hook
 * License: GPL2
*/

/*	Copyright 2015  Matthew Treherne  (email : matt@sendmail.me.uk)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) exit();

if (!class_exists('WC_AC_Hook') && in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option( 'active_plugins' ) ) ) ) :

class WC_AC_Hook {

	const OPTION_NAME = 'woocommerce_wc-ac-hook_settings';
	
    public function __construct() {

		if (is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
			// Add settings fields for this plugin to the WooCommerce Settings Integration tab
			add_action( 'plugins_loaded', array( $this, 'init_integration' ) );
			// Add the settings link to the plugins page
			add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array ($this, 'settings_link'));
			// Add custom 'tag' field to the Advanced Product data section of WooCommerce
			add_action( 'woocommerce_product_options_advanced', array ($this, 'product_advanced_field'));
			add_action( 'woocommerce_process_product_meta', array ($this, 'custom_product_fields_save')); 
		}
		// Call the ActiveCampaign API whenever an order is completed
		add_action('woocommerce_order_status_completed', array ($this, 'order_completed'));
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc-ac-hook', false, basename(dirname(__FILE__)) . '/languages/' );
	}

	public function init_integration() {
		if ( class_exists( 'WC_Integration' ) )
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
	}

	public function add_integration( $integrations ) {
		include_once 'includes/settings.php';
		$integrations[] = 'WC_AC_Hook_Integration';
		return $integrations;
	}
	
    public function settings_link($links) {
        array_unshift($links, '<a href="admin.php?page=wc-settings&tab=integration">Settings</a>'); 
        return $links;
    }
	
	public function product_advanced_field() {
		// Could modify to check only for simple product
		echo '<div class="options_group">';
		woocommerce_wp_text_input(array(
			'id' 			=> 'activecampaign_tag',
			'label' 		=> __( 'ActiveCampaign Tag', 'wc-ac-hook' ),
			'desc_tip' 		=> 'true',
			'description' 	=> __( 'Contact will be given this tag on ActiveCampaign when an order is completed', 'wc-ac-hook' )));
		echo '</div>';
	  }

	public function custom_product_fields_save( $post_id ){
		$woocommerce_text_field = $_POST['activecampaign_tag'];
		if( !empty( $woocommerce_text_field ) )
			update_post_meta( $post_id, 'activecampaign_tag', esc_attr( $woocommerce_text_field ) );
	}


	// This function is called whenever a WooCommerce order is completed
	public function order_completed ($order_id) {
		$valid_order = true;
		$log_message = array();

		// Get the plugin settings and order details
		$options = get_option( self::OPTION_NAME, null );
		$tags = $options['ac_default_tag'];
		$logging_enabled = $options['wc_ac_notification'];
		$order = new WC_Order( $order_id );

		// Add the product tags for any of the items on the order
		$items = $order->get_items();
		foreach ($items as $item) $tags .= ','.get_post_meta( $item['product_id'], 'activecampaign_tag', true );
			
		// eMail is the key on ActiveCampaign so should be validated
		if (!is_email ($order->billing_email)) {
			$valid_order = false;
			$log_message[] = sprintf( __( 'Error: Invalid customer (billing) email address = %s', 'wc-ac-hook' ), $order->billing_email);
		}

		// The order details are used to make a call using the ActiveCampaign API to add/update a customer contact
		if ($valid_order) {
			include_once('includes/sync-contact.php');
			$api = new WC_AC_Hook_Sync($options);
			$contact = array(
				'email' 				=> $order->billing_email,
				'first_name'			=> $order->billing_first_name,
				'last_name' 			=> $order->billing_last_name,
				'tags' 					=> $tags,
				'phone' 				=> $order->billing_phone);
			$api->sync_contact($contact);
			$log_message = $api->log_message;
		}

		if ($logging_enabled != 'no') {
			$log = new WC_Logger();
			$log_string = '';
			$log_message[] = sprintf( __( 'Order ID = %s', 'wc-ac-hook' ), $order_id);
			foreach (array_reverse($log_message) as $value) $log_string .= $value.PHP_EOL;
			$log->add( 'wc-ac-hook', $log_string);
		}
		
	}
	
}

new WC_AC_Hook();

endif;
?>