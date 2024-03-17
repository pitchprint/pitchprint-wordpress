<?php

namespace functions\scripts;

function add_cat_script() {
    if ( get_option('ppa_cat_customize') == 'on' )
        wp_enqueue_script('pitchprint_cat_client', PP_CAT_CLIENT_JS);
}