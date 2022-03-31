<?php
	require_once('../../wp-load.php');
	
	$ids = json_decode($_GET['ids']);
	if(!is_array($ids)) die();

	$x = array();
	$y = array();
	foreach($ids as $id) {
		$x[$id] = get_post_meta($id, '_w2p_set_option', true);
		$y[$id] = get_post_permalink($id);
	}
	
	echo json_encode(array(
		'urls'=>$y,
		'designs'=>$x,
		'apiKey'=>get_option('ppa_api_key')
	));
	
	die();
?>