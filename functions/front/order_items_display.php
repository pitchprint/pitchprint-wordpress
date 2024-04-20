<?php

namespace pitchprint\functions\front;

function order_items_display($output, $item) {
	if ( is_array($item) ) {
		if ( isset($item['_w2p_set_option']) )
			$output .= '<br/><a href="#" id="' . $item['_w2p_set_option'] . '" class="button pp-cart-data"></a>';
	}
	else
		foreach ($item->get_meta_data() as $meta_id => $meta) {
			if ($meta->key === '_w2p_set_option') {
				$output .= '<span style="display:none" data-pp="' . rawurlencode($meta->value) . '" class="pp-cart-order"></span>';
			}
		}
	return $output;
}