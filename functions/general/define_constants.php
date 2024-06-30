<?php

namespace pitchprint\functions\general;

function define_constants() {
    define('PP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    define('PP_IOBASE', 'https://pitchprint.io');
    define('PP_CLIENT_JS', 'https://pitchprint.io/rsc/js/client.js');
    define('PP_CAT_CLIENT_JS', 'https://pitchprint.io/rsc/js/cat-client.js');
    define('PP_NOES6_JS', 'https://pitchprint.io/rsc/js/noes6.js');
    define('PP_ADMIN_JS', 'https://pitchprint.io/rsc/js/a.wp.js');
    define('PPADMIN_DEF', "var PPADMIN = window.PPADMIN; if (typeof PPADMIN === 'undefined') window.PPADMIN = PPADMIN = { version: '9.0.0', readyFncs: [] };");
    define('PP_DB_VERSION', '10.3.1');
}
    