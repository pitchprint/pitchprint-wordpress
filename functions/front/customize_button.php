<?php

	namespace pitchprint\functions\front;

	use pitchprint\functions\general as General;

	function customize_button() {
		global $post;
		global $pitchprint;

		$mode = 'new';
		$now_value = '';
		$set_option = get_post_meta( $post->ID, '_w2p_set_option', true );
		$display_option = get_post_meta( $post->ID, '_w2p_display_option', true );
		$customization_required = get_post_meta( $post->ID, '_w2p_required_option', true );
		$pdf_download = get_post_meta( $post->ID, '_w2p_pdf_download_option', true );
		$use_design_preview = get_post_meta( $post->ID, '_w2p_use_design_preview_option', true );
		
		$customization_required = 
			( $customization_required === 'undefined' || 
			( isset($customization_required) && strlen($customization_required) == 0) ) ? 0 : ( $customization_required ? 1 : 0 );
		$pdf_download = 
			( $pdf_download === 'undefined' || 
			( isset($pdf_download) && strlen($pdf_download) == 0 ) ) ? 'undefined' : ( $pdf_download ? 1: 0 );
		$use_design_preview = 
			( $use_design_preview === 'undefined' || 
			( isset($use_design_preview) && strlen($use_design_preview) == 0 ) ) ? 'undefined' : ( $use_design_preview ? 1: 0 );
		
		if (strpos($set_option, ':') === false) $set_option = $set_option . ':0';
		$set_option = explode(':', $set_option);
		if (count($set_option) === 2) $set_option[2] = 0;

		$project_id = '';
		$user_id = get_current_user_id() === 0 ? 'guest' : get_current_user_id();
		$previews = '';
		$upload_ready = false;

		$lang_code = substr(get_bloginfo('language'), 0, 2);
		if (!$lang_code) $lang_code = 'en';
		
		// get user data
		$user_data = get_user_data();

		// get project data
		$project_data = General\get_customization_data($post->ID);

		if (isset($project_data) && $project_data !== FALSE) {
			if (isset($project_data['type']) && $project_data['type'] == 'u') {
				$mode = 'upload';
			} else {
				$project_id = isset($project_data['projectId']) ? $project_data['projectId'] : '';
				$mode		= isset($project_data['mode']) ? $project_data['mode'] : 'edit';
				$previews	= isset($project_data['numPages']) ? $project_data['numPages'] : '';
			}
			$now_value = urlencode(json_encode($project_data));
		}

		$design_id = $set_option[0];
		$design_id = apply_filters('set_pitchprint_design_id', $design_id);

		wc_enqueue_js("
			ajaxsearch = undefined;
			(function(_doc) {
				if (typeof PitchPrintClient === 'undefined') return;
				window.ppclient = new PitchPrintClient({
					adminUrl: '" . admin_url('admin-ajax.php') ."',
					displayMode: '{$display_option}',
					customizationRequired: {$customization_required},
					pdfDownload: {$pdf_download},
					useDesignPrevAsProdImage: {$use_design_preview},
					uploadUrl: '" . site_url('pitchprint/uploader/') . "',
					userId: '{$user_id}',
					langCode: '{$lang_code}',
					enableUpload: {$set_option[1]},
					mode: '{$mode}',
					designId: '{$design_id}',
					previews: '{$previews}',
					projectId: '{$project_id}',
					createButtons: true,
					pluginRoot: '" . site_url() . "/pitchprint/',
					apiKey: '" . get_option('ppa_api_key') . "',
					client: 'wp',
					productId: '" . $post->ID . "',
					cookieKey: '" . PITCHPRINT_CUSTOMIZATION_KEY . "',
					product: {
						id: '" . $post->ID . "',
						name: '{$post->post_name}'
					},
					userData: {$user_data},
					ppValue: '{$now_value}',
				});
			})(document);");

		echo '<input type="hidden" id="_w2p_set_option" name="_w2p_set_option" value="' . $now_value . '" />
				<div id="pp_main_btn_sec" class="ppc-main-btn-sec" ></div>';
	}

	function get_user_data() {
		if (!is_user_logged_in()) return "null";

		$customer = WC()->customer;
		$current_user = wp_get_current_user();
		
		$fname = esc_js($customer->get_billing_first_name());
		$lname = esc_js($customer->get_billing_last_name());
		
		$address = $customer->get_billing_address_1() . "<br>";
		if ( !empty($customer->get_billing_address_2()) ) {
			$address .= $customer->get_billing_address_2() . "<br>";
		}
		$address .= $customer->get_billing_city() . " " . $customer->get_billing_postcode() . "<br>";
		if ( !empty($customer->get_billing_state()) ) {
			$address .= $customer->get_billing_state() . "<br>";
		}
		$address .= $customer->get_billing_country();
		$address = esc_js($address);
		
		return "{
			email: '" . esc_js($current_user->user_email) . "',
			name: '{$fname} {$lname}',
			firstname: '{$fname}',
			lastname: '{$lname}',
			phone: '" . esc_js($customer->get_billing_phone()) . "',
			address: '{$address}'.split('&lt;br&gt;').join('\\n')
		}";
	}

	function add_cat_script() {
		if (get_option('ppa_cat_customize') == 'on')
			wp_enqueue_script('pitchprint_cat_client', PITCHPRINT_CAT_CLIENT_JS, '', '', true);
	}