<?php

namespace pitchprint\functions\general;

function clearProjects($productId) {
	global $PitchPrint;
    global $wpdb;
    $sessId = isset($_COOKIE['pitchprint_sessId']) ? $_COOKIE['pitchprint_sessId'] : false;
    if (!$sessId) return false;
    $wpdb->delete($PitchPrint->ppTable, array('id' => $sessId, 'product_id' => $productId) );
}