<?php
/**
 * PitchPrint File Upload Handler
 * Minimal, secure file upload endpoint.
 */

error_reporting(E_ERROR | E_PARSE);

// Load WordPress
$wp_load_paths = array(
    dirname(__FILE__) . '/../../../../wp-load.php',       // from plugin dir
    dirname(__FILE__) . '/../../../wp-load.php',          // from root pitchprint/ dir
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    http_response_code(500);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(array('files' => array(array('error' => 'Method not allowed'))));
    exit;
}

require('UploadHandler.php');

$handler = new PitchPrintUploader(array(
    'upload_dir'  => dirname(__FILE__) . '/files/',
    'upload_url'  => site_url(str_replace(ABSPATH, '/', dirname(__FILE__))) . '/files/',
    'thumb_dir'   => dirname(__FILE__) . '/files/thumbnail/',
    'thumb_url'   => site_url(str_replace(ABSPATH, '/', dirname(__FILE__))) . '/files/thumbnail/',
    'thumb_max'   => 450,
    'accept_types' => '/\.(gif|jpe?g|png|svg|psd|tif|tiff|bmp|cdr|ai|eps|pdf|ps|zip|gzip|rar)$/i',
    'max_file_size' => 50 * 1024 * 1024, // 50 MiB
));

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
echo json_encode($handler->handle());
