<?php

namespace pitchprint\functions\front;

function cart_item_permalink($link, $val) {
	if (!empty($val['_w2p_set_option'])) {
		$itm = $val['_w2p_set_option'];
		$itm = json_decode(rawurldecode($itm), true);
		if ($itm['type'] == 'p') {
			$link .=  (strpos($link, '?') === false ? '?' : '&') .'pitchprint=' . $itm['projectId'];
		}
	}
	return $link;
}