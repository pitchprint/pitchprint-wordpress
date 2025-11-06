<?php

    namespace pitchprint\functions\front;

    use pitchprint\functions\general as General;

    function add_cart_item_data($cart_item_data, $product_id, $variation_id, $qty) {
    
        $value = General\get_customization_data($product_id);

        if (isset($value) && $value !== FALSE) {
            $cart_item_data[PITCHPRINT_CUSTOMIZATION_KEY] = $value;
            
            // Only delete if we're actually adding to cart (not just checking)
            if (doing_action('woocommerce_add_to_cart')) {
                General\delete_customization_data($product_id);
            }
        }

        return $cart_item_data;
    }
    