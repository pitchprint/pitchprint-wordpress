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

    // Copy getProdDesigns.php to root so it can be accessible externally
    $localProdDesignsScript = ABSPATH . 'wp-content/plugins/pitchprint/app/getProdDesigns.php';
    $rootProdDesignsScript = ABSPATH . 'pitchprint/app/getProdDesigns.php';
    if (!file_exists($rootProdDesignsScript)) {

        if (!file_exists(ABSPATH . 'pitchprint')) mkdir(ABSPATH . 'pitchprint');

        if (!file_exists(ABSPATH . 'pitchprint/app')) mkdir(ABSPATH . 'pitchprint/app');
        
        copy($localProdDesignsScript, $rootProdDesignsScript);
    }

    // Copy uploader folder and its subdirectories to the root so it can be accessible externally
    $localUploaderFolder = ABSPATH . 'wp-content/plugins/pitchprint/uploader';
    $rootUploaderFolder = ABSPATH . 'pitchprint/uploader';

    if (!file_exists($rootUploaderFolder)) {
        mkdir($rootUploaderFolder, 0755, true);

        $files = scandir($localUploaderFolder);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $sourcePath = $localUploaderFolder . '/' . $file;
                $destinationPath = $rootUploaderFolder . '/' . $file;

                if (is_dir($sourcePath)) {
                    // Recursively copy subdirectories (e.g., 'files')
                    mkdir($destinationPath, 0755, true);
                    $subFiles = scandir($sourcePath);
                    foreach ($subFiles as $subFile) {
                        if ($subFile !== '.' && $subFile !== '..') {
                            copy($sourcePath . '/' . $subFile, $destinationPath . '/' . $subFile);
                        }
                    }
                } else {
                    // Copy regular files
                    copy($sourcePath, $destinationPath);
                }
            }
        }
    }
}
