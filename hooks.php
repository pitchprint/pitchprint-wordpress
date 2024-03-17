<?php
namespace hooks;

init_hooks() {
    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );
    
    if ($this->request_type('frontend')) {
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'pp_get_cart_item_from_session'), 10, 2);
        add_filter('woocommerce_get_item_data',  array($this, 'pp_get_cart_mod'), 10, 2);
        add_filter('woocommerce_cart_item_thumbnail', array($this, 'pp_cart_thumbnail'), 70, 2);
        add_filter('woocommerce_cart_item_permalink', array($this, 'pp_cart_item_permalink'), 70, 2);
        add_filter('woocommerce_add_cart_item_data', array($this, 'pp_add_cart_item_data'), 10, 2);

        add_filter('woocommerce_display_item_meta', array($this, 'pp_order_items_display'), 10, 2);
        add_filter('woocommerce_order_items_meta_display', array($this, 'pp_order_items_meta_display'), 10, 2);
        add_filter('woocommerce_order_details_after_order_table', array($this, 'pp_order_after_table'));

        add_action('woocommerce_before_my_account',  array($this, 'pp_my_recent_order'));
        add_action('woocommerce_add_order_item_meta', array($this, 'pp_add_order_item_meta'), 70, 2);

        add_action('wp_head', array($this, 'pp_header_files'));
        add_action('woocommerce_before_add_to_cart_button', array($this, 'add_pp_edit_button'));
        add_action('woocommerce_after_cart', array($this, 'pp_get_cart_action'));
        add_action('woocommerce_after_checkout_form', array($this, 'pp_get_cart_action'));
        add_action('woocommerce_before_shop_loop', 'functions\\scripts\\add_cat_script');
        add_action('wp_ajax_nopriv_pitch_print_save_project', 'functions\\session\\save');
        add_action('wp_ajax_pitch_print_save_project', 'functions\\session\\save');
    } else if ($this->request_type('admin')) {
        add_action('admin_menu', array($this, 'ppa_actions'));
        add_action('woocommerce_admin_order_data_after_order_details', array($this, 'ppa_order_details'));
        add_action('woocommerce_product_options_pricing', array($this, 'ppa_design_selection'));
        add_action('woocommerce_process_product_meta', array($this, 'ppa_write_panel_save'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'ppa_add_settings_link'));
        add_action('admin_init', array($this, 'ppa_settings_api_init'));
    }
    add_action('woocommerce_order_status_changed', 'functions\\webhooks\\pp_order_status_completed',10,3);
}
    