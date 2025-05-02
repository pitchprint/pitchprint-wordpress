<?php

    namespace pitchprint\functions\general;

    function init_hooks() {

        if (\pitchprint\functions\general\request_type('admin')) {
            
            // creates the pitchprint link and page in admin
            add_action('admin_menu', 'pitchprint\\functions\\admin\\actions');

            // creates the settings fields in the pitchprint page
            add_action('admin_init', 'pitchprint\\functions\\admin\\settings_api_init');

            // adds the link to the plugin listing page
            add_filter('plugin_action_links_pitchprint/pitchprint.php',  'pitchprint\\functions\\admin\\add_settings_link');

            // add a tab to the product data metabox
            add_filter('woocommerce_product_data_tabs', 'pitchprint\\functions\\admin\\add_design_selection_tab', 10, 1);
            
            // add the design selection form to the product data metabox
			add_action('woocommerce_product_data_panels', 'pitchprint\\functions\\admin\\design_selection', 10, 1);

            // save the design selection data
			add_action('woocommerce_process_product_meta',  'pitchprint\\functions\\admin\\ppa_write_panel_save');

            // rename the customization key to PitchPrint Customization
			add_filter('woocommerce_order_item_display_meta_key', 'pitchprint\\functions\\admin\\format_pitchprint_order_key', 20, 3);
			
            // extract and inject the customization data into the order item
            add_filter('woocommerce_order_item_get_formatted_meta_data', 'pitchprint\\functions\\admin\\format_pitchprint_order_value', 20, 2);

            add_action('woocommerce_admin_order_data_after_order_details', 'pitchprint\\functions\\admin\\legacy_order_value');

        } else if (\pitchprint\functions\general\request_type('frontend')) {

            // add the pitchprint header files
            add_action('wp_head',  'pitchprint\\functions\\front\\header_files');

            add_action('woocommerce_before_add_to_cart_button', 'pitchprint\\functions\\front\\customize_button');

            // add the product customization data to the cart item
            add_filter('woocommerce_add_cart_item_data', 'pitchprint\\functions\\front\\add_cart_item_data', 10, 4);

            // change the cart thumbnail to the customized image
            add_filter('woocommerce_cart_item_thumbnail', 'pitchprint\\functions\\front\\cart_item_thumbnail', 10, 3);

            // add the customization data to the order item
            add_filter('woocommerce_checkout_create_order_line_item', 'pitchprint\\functions\\front\\add_order_item_meta', 70, 2);
            
            add_action('woocommerce_before_shop_loop', 'pitchprint\\functions\\front\\add_cat_script');
            
            // my recent order page..
            add_action('woocommerce_before_my_account', 'pitchprint\\functions\\front\\my_recent_order');
        }

        // save project for both authenticated and guest users
        add_action('wp_ajax_nopriv_pitch_print_save_project', 'pitchprint\\functions\\front\\save_project_sess');
        add_action('wp_ajax_pitch_print_save_project', 'pitchprint\\functions\\front\\save_project_sess');
        add_action('wp_ajax_nopriv_pitch_print_reset_project', 'pitchprint\\functions\\front\\reset_project_sess');
        add_action('wp_ajax_pitch_print_reset_project', 'pitchprint\\functions\\front\\reset_project_sess');

        add_action('woocommerce_order_status_changed', 'pitchprint\\functions\\admin\\order_status_completed',10,3);
        add_action('woocommerce_new_order', 'pitchprint\\functions\\admin\\handle_new_order',10,2);

        // add the customization info to the order email
        add_action('woocommerce_email_order_details', 'pitchprint\\functions\\general\\order_email', 10, 4);
    }
    
        