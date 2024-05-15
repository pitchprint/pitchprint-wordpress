<?php

    /*
	* Plugin Name: PitchPrint
	* Plugin URI: https://pitchprint.com
	* Description: A beautiful web based print customization app for your online store. Integrates with WooCommerce.
	* Author: PitchPrint
	* Version: 10.2.3
	* Author URI: https://pitchprint.com
	* Requires at least: 3.8
	* Tested up to: 6.4
    * WC requires at least: 3.0.0
    * WC tested up to: 8.0.2
	*
	* @package PitchPrint
	* @category Core
	* @author PitchPrint
    */
       
        
	/**
	 * Begin including function files.
 	*/ 

	// Include all general function files
	foreach (glob(__DIR__ . "/functions/general/*.php") as $filename)
	{
	    include $filename;
	}
	
	// Include all front function files
	foreach (glob(__DIR__ . "/functions/front/*.php") as $filename)
	{
	    include $filename;
	}
	
	// Include all admin function files
	foreach (glob(__DIR__ . "/functions/admin/*.php") as $filename)
	{
	    include $filename;
	}

	load_plugin_textdomain('PitchPrint', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	
	add_action('wp_logout','pitchprint\\functions\\general\\end_session');

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	class PitchPrint {
		public $version = '10.2.0';
		public $editButtonsAdded = false;
		public $ppTable;

		public function __construct() {
			global $wpdb;
			
			$this->ppTable = $wpdb->prefix . 'pitchprint_projects';
			
			\pitchprint\functions\general\define_constants();
			
			// Added support for WooCommerce High-Performance order storage feature
			add_action( 'before_woocommerce_init', function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				    }
			});
			
			\pitchprint\functions\general\init_hooks();
		}
	}
	
	global $PitchPrint;
	$PitchPrint = new PitchPrint();

	register_activation_hook( __FILE__, 'pitchprint\\functions\\general\\install');

	add_action('init','pitchprint\\functions\\general\\register_session', 0);
	
	add_action('plugins_loaded', 'pitchprint\\functions\\general\\do_update');
