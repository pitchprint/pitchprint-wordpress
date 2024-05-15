<?php

namespace pitchprint\functions\general;

function fetch_credentials() {
	$timestamp = time();
	$api_key = get_option('ppa_api_key');
	$secret_key = get_option('ppa_secret_key');
	return array( 'signature' => md5($api_key . $secret_key . $timestamp), 'timestamp' => $timestamp);
}
