<?php

    namespace pitchprint\functions\front;

    function cart_item_thumbnail($product_thumbnail, $cart_item_data, $cart_item_key) {
        error_log("Cart item thumbnail filter triggered!");
        die();

        if (!empty($cart_item_data[PITCHPRINT_CUSTOMIZATION_KEY])) {
            $project_id = $cart_item_data[PITCHPRINT_CUSTOMIZATION_KEY]['projectId'];

            if (!empty($project_id)) {
                $url = PITCHPRINT_PREVIEWS_BASE . $project_id . '_1.jpg';

                if (isset($url) && !empty($url))
                    $product_thumbnail = '<img src="' . $url . '" alt="PitchPrint Preview" class="attachment-shop_thumbnail wp-post-image" />';
            }
        }
        return $product_thumbnail;
    }