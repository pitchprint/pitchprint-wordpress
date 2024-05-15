<?php

namespace pitchprint\functions\front;

function add_order_item_meta($item_id, $cart_item) {
	global $woocommerce;
	if	( !empty($cart_item['_w2p_set_option']) )
		wc_add_order_item_meta($item_id, '_w2p_set_option', $cart_item['_w2p_set_option']);
	if	( gettype($cart_item) == 'object' && isset($cart_item->legacy_values) && isset($cart_item->legacy_values['_w2p_set_option']) )
		wc_add_order_item_meta($item_id, '_w2p_set_option', $cart_item->legacy_values['_w2p_set_option']);
}
