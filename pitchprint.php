<?php

/**
* 	Plugin Name: 			PitchPrint
* 	Plugin URI: 			https://pitchprint.com
* 	Description: 			A beautiful web based print customization app for your online store. Integrates with WooCommerce.
* 	Author: 				PitchPrint, Inc.
* 	Version: 				11.0.0
* 	Author URI: 			https://pitchprint.com
* 	Requires at least: 		3.8
* 	Tested up to: 			6.7
* 	WC requires at least: 	4.0
* 	WC tested up to: 		9.4
* 	Requires PHP:      		5.2.4

* 	License: 				GPLv2 or later
*
* 	@package PitchPrint
* 	@category Core
* 	@author PitchPrint
*/
       

	if (!defined('ABSPATH')) exit;	// Should not be accessed directly

	if (!class_exists('PitchPrint')) :

		// Include all general function files
		foreach (glob(__DIR__ . "/functions/general/*.php") as $filename) {
			include $filename;
		}
		
		// Include all front function files
		foreach (glob(__DIR__ . "/functions/front/*.php") as $filename) {
			include $filename;
		}
		
		// Include all admin function files
		foreach (glob(__DIR__ . "/functions/admin/*.php") as $filename) {
			include $filename;
		}

		class PitchPrint {

			/**
			 * 	PitchPrint version.
			 * 	@var string
			*/
			public $version = '11.0.0';

			/**
			 * 	The single instance of the class.
			 * 	@var PitchPrint
			*/
			protected static $_instance = null;
			
			// main constructor
			public function __construct() {
				if (self::woocommerce_did_load())
					$this->construct();
				else
					add_action('plugins_loaded' , array($this, 'construct'));
			}

			public function construct() {
				$this->define_constants();

				$this->load_plugin_textdomain();

				\pitchprint\functions\general\init_hooks();
				
				register_activation_hook(__FILE__, 'pitchprint\\functions\\general\\install');

				add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
			}

			// singleton instance
			public static function instance() {
				if (self::$_instance === null)
					self::$_instance = new PitchPrint();

				return self::$_instance;
			}

			// load the plugin text domain for translation
			private function load_plugin_textdomain() {
				load_plugin_textdomain('PITCHPRINT', false, plugin_basename(dirname(__FILE__)) . '/i18n/languages');
			}

			// check if WooCommerce is loaded and has the correct version
			private static function woocommerce_did_load() {
				return 	defined('WC_VERSION') &&
						version_compare(WC_VERSION, '4.0', '>=');
			}

			// get the plugin url
			public function plugin_url() {
				return untrailingslashit(plugins_url('/', __FILE__ ));
			}

			// add links to the plugin display
			public static function plugin_row_meta($links, $file) {
				if (plugin_basename( __FILE__ ) == $file) {
					$row_meta = array(
						'docs' => '<a href="https://docs.pitchprint.com" target="_blank" aria-label="PitchPrint Documentation">' . esc_html__('Documentation', 'pitchprint') . '</a>',
						'dashboard' => '<a href="https://admin.pitchprint.com" target="_blank" aria-label="PitchPrint Dashboard">' . esc_html__('Dashboard', 'pitchprint') . '</a>'
					);
					return array_merge($links, $row_meta);
				}
				return (array)$links;
			}

			public function define_constants() {
				define('PITCHPRINT_PLUGIN_BASENAME', plugin_basename(__FILE__));
				define('PITCHPRINT_CUSTOMIZATION_KEY', 'pitchprint_customization');
				define('PITCHPRINT_CUSTOMIZATION_PREVIEWS_KEY', 'preview');
				define('PITCHPRINT_CUSTOMIZATION_DURATION', MONTH_IN_SECONDS);
				define('PITCHPRINT_PREVIEWS_BASE', 'https://pitchprint.io/previews/');
				
				define('PITCHPRINT_ADMIN_JS', 'https://pitchprint.io/rsc/js/a.wp.js');
				define('PITCHPRINT_CLIENT_JS', 'https://pitchprint.io/rsc/js/client.js');
    			define('PITCHPRINT_CAT_CLIENT_JS', 'https://pitchprint.io/rsc/js/cat-client.js');
    			define('PITCHPRINT_NOES6_JS', 'https://pitchprint.io/rsc/js/noes6.js');
				define('PITCHPRINT_ADMIN_DEF', "var PPADMIN = window.PPADMIN; if (typeof PPADMIN === 'undefined') window.PPADMIN = PPADMIN = { version: '9.0.0', readyFncs: [] };");
			}
			
		}

	endif;

	global $pitchprint;
	$pitchprint = PitchPrint::instance();

	// Added support for WooCommerce High-Performance order storage feature
	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true );
		}
	});

