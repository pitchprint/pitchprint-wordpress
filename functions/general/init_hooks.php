<?php
namespace pitchprint\functions\general;

function init_hooks() {

    if (\pitchprint\functions\general\request_type('frontend')) {
        add_filter('woocommerce_get_cart_item_from_session', 'pitchprint\\functions\\front\\get_cart_item_from_session', 10, 2);
        add_filter('woocommerce_get_item_data',   'pitchprint\\functions\\front\\get_cart_mod', 10, 2);
        add_filter('woocommerce_cart_item_thumbnail',  'pitchprint\\functions\\front\\cart_thumbnail', 70, 2);
        add_filter('woocommerce_cart_item_permalink',  'pitchprint\\functions\\front\\cart_item_permalink', 70, 2);
        add_filter('woocommerce_add_cart_item_data',  'pitchprint\\functions\\front\\add_cart_item_data', 10, 2);

        add_filter('woocommerce_display_item_meta',  'pitchprint\\functions\\front\\order_items_display', 10, 2);
        add_filter('woocommerce_order_items_meta_display',  'pitchprint\\functions\\front\\order_items_meta_display', 10, 2);
        add_filter('woocommerce_order_details_after_order_table',  'pitchprint\\functions\\front\\order_after_table');

        add_action('woocommerce_before_my_account',   'pitchprint\\functions\\front\\my_recent_order');
        add_action('woocommerce_add_order_item_meta',  'pitchprint\\functions\\front\\add_order_item_meta', 70, 2);

        add_action('wp_head',  'pitchprint\\functions\\front\\header_files');
        add_action('woocommerce_before_add_to_cart_button',  'pitchprint\\functions\\front\\add_edit_button');
        add_action('woocommerce_after_cart',  'pitchprint\\functions\\front\\get_cart_action');
        add_action('woocommerce_after_checkout_form',  'pitchprint\\functions\\front\\get_cart_action');
        add_action('woocommerce_before_shop_loop', 'pitchprint\\functions\\front\\general\\add_cat_script');
        add_action('wp_ajax_nopriv_pitch_print_save_project', 'pitchprint\\functions\\general\\session_save');
        add_action('wp_ajax_pitch_print_save_project', 'pitchprint\\functions\\general\\session_save');
    } else if (\pitchprint\functions\general\request_type('admin')) {
        add_action('admin_menu', 'pitchprint\\functions\\admin\\actions');
        add_action('admin_init', 'pitchprint\\functions\\admin\\settings_api_init');
        add_filter('plugin_action_links_pitchprint/pitchprint.php',  'pitchprint\\functions\\admin\\add_settings_link');
        add_action('woocommerce_admin_order_data_after_order_details', 'pitchprint\\functions\\admin\\order_details');
        add_action('woocommerce_product_options_pricing', 'pitchprint\\functions\\admin\\design_selection');
        add_action('woocommerce_process_product_meta',  'pitchprint\\functions\\admin\\ppa_write_panel_save');
    }
    add_action('woocommerce_order_status_changed', 'pitchprint\\functions\\admin\\order_status_completed',10,3);
}
    