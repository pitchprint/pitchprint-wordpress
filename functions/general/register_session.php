<?php

namespace pitchprint\functions\general;

function register_session() {
	if(!isset($_COOKIE['pitchprint_sessId']))
		setcookie('pitchprint_sessId', uniqid('pp_w2p_', true), time()+60*60*24*30, '/');
}
