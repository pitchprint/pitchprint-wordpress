<?php

namespace pitchprint\functions\general;

function do_update() {
	global $PitchPrint;
	
	if ( get_site_option('pitchprint_version') != $PitchPrint->version )	
		pp_install();
}
