<?php

namespace pitchprint\functions\admin;

function actions() {
	global $pitchprint;

	add_menu_page(
	    'PitchPrint Settings', 
	    'PitchPrint', 
	    'manage_options', 
	    'pitchprint', 
	    'pitchprint\\functions\\admin\\admin_page', 
	    $pitchprint->plugin_url() . '/assets/images/icon.svg'
	);
}

function admin_page() {
	if (!class_exists('WooCommerce')) {
		echo ('<h3>This plugin depends on WooCommerce plugin. Kindly install <a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce here!</a></h3>');
		exit();
	}
	settings_errors();

	echo '<form method="post" action="options.php"><div class="wrap">';
		settings_fields('pitchprint');
		do_settings_sections('pitchprint');
		submit_button();
	echo '</div></form>';
}