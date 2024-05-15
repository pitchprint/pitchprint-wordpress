<?php

namespace pitchprint\functions\front;

function get_cart_mod( $item_data, $cart_item ) {
	if (!is_page('cart')) return $item_data;
	if (!empty($cart_item['_w2p_set_option'])) {
		$val = $cart_item['_w2p_set_option'];
		$item_data[] = array(
			'name'    => '<span id="' . $val . '" class="pp-cart-label"></span>',
			'display' => '<a href="#" id="' . $val . '" class="button pp-cart-data"></a>'
		);
	}
	return $item_data;
}