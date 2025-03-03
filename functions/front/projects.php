<?php

    namespace pitchprint\functions\front;

    use pitchprint\functions\general as General;

    function save_project_sess() {

        if (!isset($_POST['values']) || empty($_POST['values'])) {
            wp_send_json_error('No customization data provided');
        }

        if (!isset($_POST['productId']) || empty($_POST['productId'])) {
            wp_send_json_error('No product ID provided');
        }

        $value = json_decode(urldecode($_POST['values']), true);
        if (json_last_error() !== JSON_ERROR_NONE) wp_send_json_error(json_last_error());

		$product_id	= $_POST['productId'];
        $result = General\save_customization_data($product_id, $value);
		if ($result !== FALSE)
            return wp_send_json_success('customization data saved successfully: ' . $result);

        wp_send_json_error('Failed to save customization data');
    }

    function reset_project_sess() {
        if (!isset($_POST['productId']) || empty($_POST['productId'])) {
            wp_send_json_error('No product ID provided.');
        }
    
        $product_id = $_POST['productId'];
    
        General\delete_customization_data($product_id);
        wp_send_json_success('Customization data cleared successfully.');
        
    }
