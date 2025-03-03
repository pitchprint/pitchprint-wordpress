<?php

namespace pitchprint\functions\front;

function header_files() {
	global $post, $product;
	if (empty($post)) return;
	$pp_set_option = get_post_meta($post->ID, '_w2p_set_option', true);
	if (!empty($pp_set_option)) {
		wp_enqueue_script('pitchprint_class', PITCHPRINT_CLIENT_JS);
		wp_enqueue_script('pitchprint_class_noes6', PITCHPRINT_NOES6_JS);
	}
}
