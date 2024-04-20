<?php

namespace pitchprint\functions\front;

function my_recent_order() {
	
	global $post, $woocommerce;
	wp_enqueue_script('pitchprint_class', PP_CLIENT_JS);
	wp_enqueue_script('pitchprint_class_noes6', PP_NOES6_JS);
	wp_enqueue_script('prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.min.js', array( 'jquery' ), $woocommerce->version, true );
	wp_enqueue_script('prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init.min.js', array( 'jquery' ), $woocommerce->version, true );
	wp_enqueue_style('woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
	
	echo '<div id="pp_mydesigns_div"></div>';
	wc_enqueue_js("
		ajaxsearch = undefined;
		(function(_doc) {
			if (typeof PitchPrintClient === 'undefined') return;
			window.ppclient = new PitchPrintClient({
				userId: '" . (get_current_user_id() === 0 ? 'guest' : get_current_user_id())  . "',
				langCode: '" . substr(get_bloginfo('language'), 0, 2) . "',
				mode: 'edit',
				pluginRoot: '" . site_url() . "/pitchprint/',
				apiKey: '" . get_option('ppa_api_key') . "',
				client: 'wp',
				afterValidation: '_fetchProjects',
				adminUrl: '" . admin_url( 'admin-ajax.php' ) ."',
				isCheckoutPage: " . (is_checkout() ? 'true' : 'false') . "
			});
		})(document);");
}