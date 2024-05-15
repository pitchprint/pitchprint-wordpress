<?php

namespace pitchprint\functions\front;

function add_cart_item_data($cart_item_meta, $product_id) {
	$_projects = \pitchprint\functions\front\getProjectData($product_id);
	if (isset($_projects)) {
		if (isset($_projects[$product_id])) {
			$cart_item_meta['_w2p_set_option'] = $_projects[$product_id];
			if (!isset($_SESSION['pp_cache'])) $_SESSION['pp_cache'] = array();
			$opt_ = json_decode(rawurldecode($_projects[$product_id]), true);
			if ($opt_['type'] === 'p') $_SESSION['pp_cache'][$opt_['projectId']] = $_projects[$product_id] . "";
			if ( isset($_SESSION['pp_projects']) && isset($_SESSION['pp_projects'][$product_id]) )
				unset($_SESSION['pp_projects'][$product_id]);
			else 
				\pitchprint\functions\general\clearProjects($product_id);
		}
	}
	return $cart_item_meta;
}