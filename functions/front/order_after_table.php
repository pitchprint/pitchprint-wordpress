<?php

namespace pitchprint\functions\front;

function order_after_table($params) {
	$projectIds = [];

	$items = $params->items;
	$lastItem = end($items);
	if(is_object($lastItem) && method_exists($lastItem, 'get_order_id')) {
		$orderId = $lastItem->get_order_id();
	} else {
		$orderId = null;
	}
	// $orderId = array_pop($params->items)->get_order_id();
	
	$userId = get_current_user_id();
	$shouldUpdateUserId = false;
	
	foreach( $params->items as $orderItem ) {
		foreach ($orderItem->get_meta_data() as $meta_id => $meta) {
			if ($meta->key === '_w2p_set_option') {
				$ppMeta = json_decode(urldecode($meta->value));
				if ($userId && $ppMeta->userId == 'guest')
					$shouldUpdateUserId = true;
				$projectIds[] = $ppMeta->projectId;
			}
		}
	}

	if (count($projectIds)) {
		$authKey = get_option('ppa_secret_key');
		$url = 'https://api.pitchprint.com/runtime/append-project-order-id';
		wp_remote_post($url, array(
			'headers' => array('Authorization' => $authKey),
			'body'=>json_encode(array(
				'projectIds' => $projectIds,
				'orderId' => $orderId
			))
		));	
		
		if ($shouldUpdateUserId) {	
			$url = 'https://api.pitchprint.com/runtime/append-project-user-id';
			wp_remote_post($url, array(
				'headers' => array('Authorization' => $authKey),
				'body'=>json_encode(array(
					'projectIds' => $projectIds,
					'userId' => $userId
				))
			));
		}
	}

	wp_enqueue_script('pitchprint_class', PP_CLIENT_JS);
	wp_enqueue_script('pitchprint_class_noes6', PP_NOES6_JS);
	
	wc_enqueue_js("
		ajaxsearch = undefined;
		(function(_doc) {
			window.ppclient = new PitchPrintClient({
				userId: '" . (get_current_user_id() === 0 ? 'guest' : get_current_user_id())  . "',
				langCode: '" . substr(get_bloginfo('language'), 0, 2) . "',
				mode: 'edit',
				pluginRoot: '" . site_url() . "/pitchprint/',
				apiKey: '" . get_option('ppa_api_key') . "',
				client: 'wp',
				adminUrl: '" . admin_url( 'admin-ajax.php' ) ."',
				afterValidation: '_sortCart'
			});
		})(document);");
}