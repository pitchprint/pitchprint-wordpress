<?php

namespace pitchprint\functions\admin;

function ppa_create_settings() {
	echo '<p>' . __("You can generate your api and secret keys from the <a target=\"_blank\" href=\"https://admin.pitchprint.com/domains\">PitchPrint domains page</a>", "PitchPrint") . '</p>';
}

function settings_api_init() {
	add_settings_section('ppa_settings_section', 'PitchPrint Settings',  'pitchprint\\functions\\admin\\ppa_create_settings', 'pitchprint');
	add_settings_field('ppa_api_key', 'Api Key',  'pitchprint\\functions\\admin\\ppa_api_key', 'pitchprint', 'ppa_settings_section', array());
	add_settings_field('ppa_secret_key', 'Secret Key',  'pitchprint\\functions\\admin\\ppa_secret_key', 'pitchprint', 'ppa_settings_section', array());
	add_settings_field('ppa_cat_customize', 'Category Customization',  'pitchprint\\functions\\admin\\ppa_cat_customize', 'pitchprint', 'ppa_settings_section', array());
	register_setting('pitchprint', 'ppa_api_key');
	register_setting('pitchprint', 'ppa_secret_key');
	register_setting('pitchprint', 'ppa_cat_customize');
}

function ppa_api_key() {
	echo '<input class="regular-text" id="ppa_api_key" name="ppa_api_key" type="text" value="' . get_option('ppa_api_key') . '" />';
}
function ppa_secret_key() {
	echo '<input class="regular-text" id="ppa_secret_key" name="ppa_secret_key" type="text" value="' . get_option('ppa_secret_key') . '" />';
}
function ppa_cat_customize() {
	echo '<input class="regular-text" id="ppa_cat_customize" name="ppa_cat_customize" type="checkbox" '. ( get_option('ppa_cat_customize') == 'on' ? 'checked' : '' ) . ' />';
}


function add_settings_link($links) {
	$settings_link = array(
		'<a href="/wp-admin/admin.php?page=pitchprint" target="_blank" rel="noopener">Settings</a>',
		'<a href="https://docs.pitchprint.com/"  target="_blank" rel="noopener">Documentation</a>',
		'<a href="https://admin.pitchprint.com/dashboard" target="_blank" rel="noopener">Admin Dashboard & Support</a>'
	);
	$actions = array_merge( $links, $settings_link );
	return $actions;
}