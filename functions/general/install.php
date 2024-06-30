<?php

namespace pitchprint\functions\general;

function install() {
	// Copy getProdDesigns.php to root so it can be accessble externally
	$localProdDesignsScript = ABSPATH.'wp-content/plugins/pitchprint/app/getProdDesigns.php';
	$rootProdDesignsScript = ABSPATH.'pitchprint/app/getProdDesigns.php';
	if (!file_exists($rootProdDesignsScript)){
		if (!file_exists(ABSPATH.'pitchprint'))
			mkdir(ABSPATH.'pitchprint');
		if (!file_exists(ABSPATH.'pitchprint/app'))
			mkdir(ABSPATH.'pitchprint/app');
		copy($localProdDesignsScript, $rootProdDesignsScript);
	}

	// Copy saveproject.php to root so it can be accessble externally
	$localSaveProjectScript = ABSPATH.'wp-content/plugins/pitchprint/app/saveproject.php';
	$rootSaveProjectScript = ABSPATH.'pitchprint/app/saveproject.php';
	if (!file_exists($rootSaveProjectScript)){
		if (!file_exists(ABSPATH.'pitchprint'))
			mkdir(ABSPATH.'pitchprint');
		if (!file_exists(ABSPATH.'pitchprint/app'))
			mkdir(ABSPATH.'pitchprint/app');
		copy($localSaveProjectScript, $rootSaveProjectScript);
	}
	
	global $PitchPrint;
	$pp_version = get_option('pitchprint_version');
	
	if ($pp_version != $PitchPrint->version) {
		global $wpdb;

		$table_name 		= $wpdb->prefix . 'pitchprint_projects';
		$charset_collate	= $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id varchar(55) NOT NULL ,
		  product_id mediumint(9) NOT NULL,
		  value TEXT  NOT NULL,
		  expires TIMESTAMP
		) $charset_collate;";
		
		$exec = dbDelta( $sql );
	}
	if ($pp_version) 
		update_option('pitchprint_version', $PitchPrint->version);
	else
		add_option('pitchprint_version', $PitchPrint->version);

	// Update DB version
	$dbVersion = get_option('pitchprint_db_version');

	if (version_compare($dbVersion, PP_DB_VERSION, '<')) {
		\pitchprint\functions\updates\db_product_id_medint_int();
		update_option('pitchprint_db_version', PP_DB_VERSION);
	}
}
