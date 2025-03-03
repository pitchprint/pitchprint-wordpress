<?php

    namespace pitchprint\functions\front;

    function add_order_item_meta($order_item, $cart_item_key) {
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );

        if (empty($cart_item) || empty($cart_item[PITCHPRINT_CUSTOMIZATION_KEY])) return;

        $order_item->add_meta_data(PITCHPRINT_CUSTOMIZATION_KEY, $cart_item[PITCHPRINT_CUSTOMIZATION_KEY], true);

        $customization = $cart_item[PITCHPRINT_CUSTOMIZATION_KEY];

        if (!empty($customization['projectId'])) {
            $project_id = $customization['projectId'];
            $url = PITCHPRINT_PREVIEWS_BASE . $project_id . '_1.jpg';
            $preview = '<img class="pp-preview-image" style="width:120px; margin-left: 5px; margin-right:5px" src="' . $url . '">';
            $order_item->add_meta_data(PITCHPRINT_CUSTOMIZATION_PREVIEWS_KEY, $preview, true);

        } else if (isset($customization['previews']) && is_array($customization['previews'])) {
            $previews = $customization['previews'];
            $preview = '';
            foreach ($previews as $url) {
                $preview .= '<img class="pp-preview-image" style="width:120px; margin-left: 5px; margin-right:5px" src="' . $url . '"></br>';
            }
            $order_item->add_meta_data(PITCHPRINT_CUSTOMIZATION_PREVIEWS_KEY, $preview, true);
        }
		
	}