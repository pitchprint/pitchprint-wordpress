<?php

namespace pitchprint\functions\admin;

function order_details() {
	global $post, $woocommerce;
	$cred = \pitchprint\functions\general\fetch_credentials();
	wp_enqueue_script('pitchprint_admin', PP_ADMIN_JS);
	wc_enqueue_js( PPADMIN_DEF . "
		PPADMIN.vars = {
			credentials: { timestamp: '" . $cred['timestamp'] . "', apiKey: '" . get_option('ppa_api_key') . "', signature: '" . $cred['signature'] . "'}
		};
		PPADMIN.readyFncs.push('init');
		if (typeof PPADMIN.start !== 'undefined') PPADMIN.start();
	");
}