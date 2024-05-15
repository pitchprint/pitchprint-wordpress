<?php

namespace pitchprint\functions\front;

function register_session(){
	if(!session_id() && !headers_sent()) session_start();
	if(!isset($_COOKIE['pitchprint_sessId']))
	setcookie('pitchprint_sessId', uniqid('pp_w2p_', true), time()+60*60*24*30, '/');
}