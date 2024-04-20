<?php

namespace pitchprint\functions\front;

function get_cart_item_from_session($cart_item, $values) {
	if (!empty($values['_w2p_set_option'])) $cart_item['_w2p_set_option'] = $values['_w2p_set_option'];
	return $cart_item;
}