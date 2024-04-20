<?php

namespace pitchprint\functions\front;

function getProjectData($product_id = null) {
	global $PitchPrint;
	
	if (!$product_id) {
		global $post;
		$product_id = $post->ID;
	}
	
	$_projects = array();
	
	
	if ( isset($_COOKIE['pitchprint_sessId']) ) {
		global $wpdb;
		$sessId = $_COOKIE['pitchprint_sessId'];
		$sql = "SELECT `value` FROM `$PitchPrint->ppTable` WHERE `product_id` = $product_id AND `id` = '$sessId';";
		$results = $wpdb->get_results($sql);
		if(count($results))
			$_projects[$product_id] = $results[0]->value;
			
	} else {
		\pitchprint\functions\front\register_session();
		if (isset( $_SESSION['pp_projects']))
			$_projects =  $_SESSION['pp_projects'];
	}
	return $_projects;
}