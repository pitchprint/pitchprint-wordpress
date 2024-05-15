<?php
    namespace pitchprint\functions\admin;
    
    function design_selection() {
			if (!class_exists('WooCommerce')) exit;
			global $post, $woocommerce;

			wp_enqueue_script('pitchprint_admin', PP_ADMIN_JS);

			echo '</div><div class="options_group show_if_simple show_if_variable"><input type="hidden" value="' . get_post_meta( $post->ID, '_w2p_set_option', true ) . '" id="ppa_values" name="ppa_values" >';
			
			$ppa_customization_required = get_post_meta($post->ID, '_w2p_required_option', true);
			$ppa_pdf_download = get_post_meta($post->ID, '_w2p_pdf_download_option', true);
			$ppa_use_design_preview = get_post_meta($post->ID, '_w2p_use_design_preview_option', true);

			$ppa_upload_selected_option = '';
			$ppa_display_selected_option = get_post_meta($post->ID, '_w2p_display_option', true);
			//$ppa_hide_Cart_btn_option = '';
			$ppa_selected_option = get_post_meta( $post->ID, '_w2p_set_option', true );
			$ppa_selected_option = explode(':', $ppa_selected_option);
			if (count($ppa_selected_option) > 1) $ppa_upload_selected_option = ($ppa_selected_option[1] === '1' ? 'checked' : '');
			//if (count($ppa_selected_option) > 2) $ppa_hide_Cart_btn_option = ($ppa_selected_option[2] === '1' ? 'checked' : '');

			woocommerce_wp_select( array(
				'id'            => 'ppa_pick',
				'value'			=>	$ppa_selected_option[0],
				'wrapper_class' => '',
				'options'       => array('' => 'None'),
				'label'         => 'PitchPrint Design',
				'desc_tip'    	=> true,
				'description' 	=> __("Visit the PitchPrint Admin Panel to create and edit designs", 'PitchPrint')
			) );

			woocommerce_wp_checkbox( array(
				'id'            => 'ppa_pick_upload',
				'value'		    => $ppa_upload_selected_option,
				'label'         => '',
				'cbvalue'		=> 'checked',
				'description' 	=> '&#8678; ' . __("Check this to enable clients to upload their files", 'PitchPrint')
			) );

			woocommerce_wp_select( array(
				'id'            => 'ppa_pick_display_mode',
				'value'		    => $ppa_display_selected_option,
				'label'         => 'Display Mode',
				'options'       => array(''=>'Default', 'modal'=>'Full Window', 'inline'=>'Inline', 'mini'=>'Mini'),
				'cbvalue'		=> 'unchecked',
				'desc_tip'		=> true,
				'description' 	=>  __("Define the way that PitchPrint designer should open for this product on the front.")
			) );
			
			woocommerce_wp_checkbox( array(
				'id'            => 'ppa_pick_required',
				'value'		    => $ppa_customization_required,
				'label'         => '',
				'cbvalue'		=> 'checked',
				'description' 	=> '&#8678; ' . __("Check this to make customization compulsory for this product", 'PitchPrint')
			) );
			
			woocommerce_wp_checkbox( array(
				'id'            => 'ppa_pick_pdf_download',
				'value'		    => $ppa_pdf_download,
				'label'         => '',
				'cbvalue'		=> 'checked',
				'description' 	=> '&#8678; ' . __("Check this to allow PDF download for this product", 'PitchPrint')
			) );
			
			woocommerce_wp_checkbox( array(
				'id'            => 'ppa_pick_use_design_preview',
				'value'		    => $ppa_use_design_preview,
				'label'         => '',
				'cbvalue'		=> 'checked',
				'description' 	=> '&#8678; ' . __("Check this to show the PitchPrint design preview if this product has no product image", 'PitchPrint')
			) );
			
			$cred = \pitchprint\functions\general\fetch_credentials();
			wc_enqueue_js( PPADMIN_DEF . "
				PPADMIN.vars = {
					credentials: { timestamp: '" . $cred['timestamp'] . "', apiKey: '" . get_option('ppa_api_key') . "', signature: '" . $cred['signature'] . "'},
					selectedOption: '{$ppa_selected_option[0]}'
				};
				PPADMIN.readyFncs.push('init', 'fetchDesigns');
				if (typeof PPADMIN.start !== 'undefined') PPADMIN.start();
			");
		}