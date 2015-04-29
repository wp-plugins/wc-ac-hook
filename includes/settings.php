<?php
/**
 * Class for WC AC Hook settings fields in administration panel. This class will add the necessary
 * form fields to the 'Integration' tab of the WooCommerce Settings menu.
 *
 */
if (!defined('ABSPATH')) exit();

if (!class_exists('WC_AC_Hook_Integration')) :

class WC_AC_Hook_Integration extends WC_Integration {

	public function __construct() {
		$this->id                 = 'wc-ac-hook';
		$this->method_title       = __( 'WC-AC Hook Settings', 'wc-ac-hook' );
		$this->method_description = __( 'You must enter your ActiveCampaign URL and your ActiveCampaign API key to allow the WooCommerce web hook to add/update contacts when an order is completed. You may also have tags dependent on the product ordered. You will find an ActiveCampaign Tag field in the Advanced Product Data section for each WooCommerce product in your shop.', 'wc-ac-hook' );
		$this->init_form_fields();
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function init_form_fields() {
		$ac_settings = get_option('settings_activecampaign', null);
		$this->form_fields = array(
			'ac_url' => array(
				'title'             => __( 'ActiveCampaign URL', 'wc-ac-hook' ),
				'description'       => __( 'In the format "https://youraccount.activehosted.com"', 'wc-ac-hook' ),
				'type'              => 'text'
			),
			'ac_api_key' => array(
				'title'             => __( 'ActiveCampaign API Key', 'wc-ac-hook' ),
				'type'              => 'text',
				'default'      		=> $ac_settings['api_key']
			),
			'ac_list_id' => array(
				'title'             => __( 'ActiveCampaign List ID', 'wc-ac-hook' ),
				'type'              => 'text',
				'css'				=> 'width:3em',
				'description'       => __( 'Enter the ActiveCampaign list to which you would like contacts added.', 'wc-ac-hook' ),
				'desc_tip'          => true
			),
			'ac_default_tag' => array(
				'title'             => __( 'Default Tag(s)', 'wc-ac-hook' ),
				'type'              => 'text',
				'description'       => __( 'The default tags will always be added for any order (if you want multiple tags then comma separate).', 'wc-ac-hook' ),
				'desc_tip'          => true
			),
			'wc_ac_addonprocessing' => array(
				'title' 			=> __( 'Add/Update Contact', 'wc-ac-hook' ),
				'type' 				=> 'checkbox',
				'label' 			=> __( 'When order created (i.e. status is processing)', 'wc-ac-hook' ),
				'description' 		=> __( 'Default is to wait until order is completed', 'wc-ac-hook' ),
			),
			'wc_ac_notification' => array(
				'title' 			=> __( 'Debug Log', 'wc-ac-hook' ),
				'type' 				=> 'checkbox',
				'label' 			=> __( 'Enable logging', 'wc-ac-hook' ),
				'default' 			=> 'yes',
				'description' 		=> __( 'Report errors to a WooCommerce System Status log file', 'wc-ac-hook' ),
			)
		);
	}

}

endif;
?>