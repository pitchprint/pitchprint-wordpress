<?php

namespace pitchprint\functions\front;

function add_edit_button() {
    global $PitchPrint;
    
	if ($PitchPrint->editButtonsAdded) {
		return;
	}
	$PitchPrint->editButtonsAdded = true;

	global $post;
	global $woocommerce;
	$pp_mode = 'new';
	$pp_set_option = get_post_meta( $post->ID, '_w2p_set_option', true );
	$pp_display_option = get_post_meta( $post->ID, '_w2p_display_option', true );
	$pp_customization_required = get_post_meta( $post->ID, '_w2p_required_option', true );
	$pp_pdf_download = get_post_meta( $post->ID, '_w2p_pdf_download_option', true );
	$pp_use_design_preview = get_post_meta( $post->ID, '_w2p_use_design_preview_option', true );
	
	$pp_customization_required = 
		( $pp_customization_required === 'undefined' || 
		( isset($pp_customization_required) && strlen($pp_customization_required) == 0) ) ? 0 : ( $pp_customization_required ? 1 : 0 );
	$pp_pdf_download = 
		( $pp_pdf_download === 'undefined' || 
		( isset($pp_pdf_download) && strlen($pp_pdf_download) == 0 ) ) ? 'undefined' : ( $pp_pdf_download ? 1: 0 );
	$pp_use_design_preview = 
		( $pp_use_design_preview === 'undefined' || 
		( isset($pp_use_design_preview) && strlen($pp_use_design_preview) == 0 ) ) ? 'undefined' : ( $pp_use_design_preview ? 1: 0 );
	
	if (strpos($pp_set_option, ':') === false) $pp_set_option = $pp_set_option . ':0';
	$pp_set_option = explode(':', $pp_set_option);
	if (count($pp_set_option) === 2) $pp_set_option[2] = 0;
	$pp_project_id = '';
	$pp_uid = get_current_user_id() === 0 ? 'guest' : get_current_user_id();
	$pp_now_value = '';
	$pp_previews = '';
	$pp_upload_ready = false;

	$_projects = \pitchprint\functions\front\getProjectData();

	$_ppcache = "";
	$pp_now_value = "";
	
	if ( !isset($_COOKIE['pitchprint_sessId']) ) {
		\pitchprint\functions\front\register_session();
	
		if (isset($_GET['pitchprint']) && isset($_SESSION['pp_cache'])) {
			if (!empty($_GET['pitchprint']) && !empty($_SESSION['pp_cache'])) {
				if (!empty($_SESSION['pp_cache'][$_GET['pitchprint']])) $_ppcache = $_SESSION['pp_cache'][$_GET['pitchprint']];
			}
		}
	}

	if (!empty($_ppcache)) {
		$pp_now_value = $_ppcache;
	} else if (isset($_projects)) {
		if (isset($_projects[$post->ID])) {
			$pp_now_value = $_projects[$post->ID];
		}
	}
	
	$pp_design_id = $pp_set_option[0];

	if (!empty($pp_now_value)) {
		$opt_ = json_decode(rawurldecode($pp_now_value), true);
		if ($opt_['type'] === 'u') {
			$pp_upload_ready = true;
			$pp_mode = 'upload';
		} else if ($opt_['type'] === 'p') {
			$pp_mode = 'edit';
			$pp_project_id = $opt_['projectId'];
			$pp_previews = $opt_['numPages'];
			if (!isset($opt_['projectId']) && !empty($opt_['projectId'])) $pp_design_id = $opt_['projectId'];
		}
	}

	$userData = '';

	if (is_user_logged_in()) {
		global $current_user;
		wp_get_current_user();
		$fname = addslashes($woocommerce->customer->get_billing_first_name());
		$lname = addslashes($woocommerce->customer->get_billing_last_name());
		$address_1 = $woocommerce->customer->get_billing_address_1();
		$address_2 = $woocommerce->customer->get_billing_address_2();
		$city = $woocommerce->customer->get_billing_city();
		$postcode = $woocommerce->customer->get_billing_postcode();
		$state = $woocommerce->customer->get_billing_state();
		$country = $woocommerce->customer->get_billing_country();
		$phone = $woocommerce->customer->get_billing_phone();

		$address = "{$address_1}<br>";
		if (!empty($address_2)) $address .= "{$address_2}<br>";
		$address .= "{$city} {$postcode}<br>";
		if (!empty($state)) $address .= "{$state}<br>";
		$address .= $country;
		$address = addslashes($address);

		$userData = ",
			userData: {
				email: '" . $current_user->user_email . "',
				name: '{$fname} {$lname}',
				firstname: '{$fname}',
				lastname: '{$lname}',
				telephone: '{$phone}',
				address: '{$address}'.split('<br>').join('\\n')
			}";
	}
	
	// $miniMode = $pp_set_option[0] === "3d8f3899904ef2392795c681091600d0" ? '\'mini\'' : 'undefined';
	
	$pp_design_id = apply_filters('set_pitchprint_design_id', $pp_design_id);
	
	wc_enqueue_js("
		ajaxsearch = undefined;
		(function(_doc) {
			if (typeof PitchPrintClient === 'undefined') return;
			window.ppclient = new PitchPrintClient({
				adminUrl: '" . admin_url( 'admin-ajax.php' ) ."',
				displayMode: '{$pp_display_option}',
				customizationRequired: ". $pp_customization_required.",
				pdfDownload: ". $pp_pdf_download .",
				useDesignPrevAsProdImage: " . $pp_use_design_preview. ",
				uploadUrl: '" . plugins_url('uploader/', __FILE__) . "',
				userId: '{$pp_uid}',
				langCode: '" . substr(get_bloginfo('language'), 0, 2) . "',
				enableUpload: {$pp_set_option[1]},
				designId: '{$pp_design_id}',
				previews: '{$pp_previews}',
				mode: '{$pp_mode}',
				createButtons: true,
				projectId: '{$pp_project_id}',
				pluginRoot: '" . site_url() . "/pitchprint/',
				apiKey: '" . get_option('ppa_api_key') . "',
				client: 'wp',
				product: {
					id: '" . $post->ID . "',
					name: '{$post->post_name}'
				}{$userData},
				ppValue: '{$pp_now_value}'
			});
		})(document);");
	echo '
		<input type="hidden" id="_w2p_set_option" name="_w2p_set_option" value="' . $pp_now_value . '" />
		<div id="pp_main_btn_sec" class="ppc-main-btn-sec" > </div>';
}