<?php

namespace pitchprint\functions\general;

function install() {
    // Ensure the required file is included
    if (!function_exists('is_plugin_active')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    // Check if WooCommerce is active
    if (!\is_plugin_active('woocommerce/woocommerce.php')) {
        \deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('This plugin requires WooCommerce to be installed and active. Please install WooCommerce and activate it before activating this plugin.', 'pitchprint'));
    }

    // Copy getProdDesigns.php to root so it can be accessble externally
    $localProdDesignsScript = ABSPATH.'wp-content/plugins/pitchprint/app/getProdDesigns.php';
    $rootProdDesignsScript = ABSPATH.'pitchprint/app/getProdDesigns.php';
    if (!file_exists($rootProdDesignsScript)) {

        if (!file_exists(ABSPATH.'pitchprint')) mkdir(ABSPATH.'pitchprint');

        if (!file_exists(ABSPATH.'pitchprint/app')) mkdir(ABSPATH.'pitchprint/app');
        
        copy($localProdDesignsScript, $rootProdDesignsScript);
    }
}
