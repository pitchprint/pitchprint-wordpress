<?php

namespace pitchprint\functions\general;

function end_session() {
	if(session_id()) session_destroy();
}