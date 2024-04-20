<?php

namespace pitchprint\functions\front;

function order_items_meta_display($output, $_this) {
    if (isset($_this->meta['_w2p_set_option'])) {
        if (!empty($_this->meta['_w2p_set_option'])) {
            $val = $_this->meta['_w2p_set_option'][0];
            $val = rawurlencode($val);
            $output .= '<span  style="display:none" data-pp="' . $val . '" class="pp-cart-order"></span>';
        }
    }
    return $output;
}