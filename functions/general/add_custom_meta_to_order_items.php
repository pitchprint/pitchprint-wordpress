<?php

namespace pitchprint\functions\general;

function add_custom_meta_to_order_items( $item, $cart_item_key, $values, $order ) {
	if (!empty($values['_w2p_set_option'])) {
        $item->add_meta_data('_w2p_set_option', $values['_w2p_set_option'], true);
    }
}