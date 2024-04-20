<?php

namespace pitchprint\functions\front;

function get_cart_action() {
	global $post, $woocommerce;
	wp_enqueue_script('pitchprint_class', PP_CLIENT_JS);
	wp_enqueue_script('pitchprint_class_noes6', PP_NOES6_JS);
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
				afterValidation: '_sortCart',
				adminUrl: '" . admin_url( 'admin-ajax.php' ) ."',
				isCheckoutPage: " . (is_checkout() ? 'true' : 'false') . "
			});
		})(document);");
}