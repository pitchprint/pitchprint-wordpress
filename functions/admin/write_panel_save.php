<?php

namespace pitchprint\functions\admin;

function ppa_write_panel_save( $post_id ) {
	update_post_meta($post_id, '_w2p_set_option', $_POST['ppa_values']);
	if ( ! add_post_meta( $post_id, '_w2p_display_option', $_POST['ppa_pick_display_mode'], true ) ) { 
	  update_post_meta( $post_id, '_w2p_display_option', $_POST['ppa_pick_display_mode']);
	}
	
	$reqCVal = get_post_meta($post_id, '_w2p_required_option', true);
	$reqNVal = isset($_POST['ppa_pick_required']) ? $_POST['ppa_pick_required']: NULL;
	
	if($reqCVal !== NULL && $reqCVal !== 'checked' && strlen($reqNVal) == 0)
		$reqNVal = 'undefined';
	if ( ! add_post_meta( $post_id, '_w2p_required_option',$reqNVal, true ) ) { 
	  update_post_meta( $post_id, '_w2p_required_option', $reqNVal);
	}
	
	$downlCVal = get_post_meta($post_id, '_w2p_pdf_download_option', true);
	$downlNVal = isset($_POST['ppa_pick_pdf_download']) ? $_POST['ppa_pick_pdf_download']: NULL;
	if($downlCVal !== NULL && $downlCVal !== 'checked' && strlen($downlNVal) == 0 )
		$downlNVal = 'undefined';
	if ( ! add_post_meta( $post_id, '_w2p_pdf_download_option', $downlNVal, true ) ) { 
	  update_post_meta( $post_id, '_w2p_pdf_download_option', $downlNVal);
	}
	
	$useDesignPrevCVal = get_post_meta($post_id, '_w2p_use_design_preview_option', true);
	$useDesignPrevCValNVal = isset($_POST['ppa_pick_use_design_preview']) ? $_POST['ppa_pick_use_design_preview'] : NULL;
	if($useDesignPrevCVal !== NULL && $useDesignPrevCVal !== 'checked' && strlen($useDesignPrevCValNVal) == 0 )
		$useDesignPrevCValNVal = 'undefined';
	if ( ! add_post_meta( $post_id, '_w2p_use_design_preview_option', $useDesignPrevCValNVal, true ) )  
	  update_post_meta( $post_id, '_w2p_use_design_preview_option', $useDesignPrevCValNVal);
}