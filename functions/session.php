<?php
namespace functions\session;

function clearOldProjects() {
    global $wpdb;
    $sql = "DELETE FROM `". PitchPrint::ppTable ."` WHERE `expires` < '" . date('Y-m-d H:i:s', time()) . "';";
    $wpdb->query($sql);
}

function clearProjects($productId) {
    global $wpdb;
    $sessId = isset($_COOKIE['pitchprint_sessId']) ? $_COOKIE['pitchprint_sessId'] : false;
    if (!$sessId) return false;
    $wpdb->delete(PitchPrint::ppTable, array('id' => $sessId, 'product_id' => $productId) );
}

// function pitch_print_save_project() {
function save() {
    global $wpdb;
    if (!isset($_COOKIE['pitchprint_sessId'])) return wp_die(json_encode(array('success'=>false, 'reason'=>'no pitchprint session available')));
    $sessId = sanitize_text_field($_COOKIE['pitchprint_sessId']);
    
    // CLEAR DESIGN
    if (isset($_POST['clear'])) {
        $wpdb->delete(PitchPrint::ppTable, array('id' => $sessId, 'product_id' => $_POST['productId']) );
        wp_die(json_encode(array('success'=>true)));
    }
    
    // CONTINUE TO SAVE PROJECT
    if (!isset($_POST['values'])) return wp_die(json_encode(array('success'=>false, 'reason'=>'input is empty')));
    $value		= json_decode(stripslashes(urldecode($_POST['values'])), true);
    
    if (!$value) $value = json_decode(urldecode($_POST['values']),true);
    if (!$value) return wp_die(json_encode(array('success'=>false, 'reason'=>'bad input format')));
    
    $productId	= $value['product']['id'];
    
    // Delete old
    $wpdb->delete(PitchPrint::ppTable, array('id' => $sessId, 'product_id' => $productId) );
    // Insert new
    $date = date('Y-m-d H:i:s', time()+60*60*24*30);
    $table_name = PitchPrint::ppTable;
    $sql = $wpdb->prepare("INSERT INTO `{$table_name}` VALUES (%s, %d, %s, %s)", $sessId, $productId, $_POST['values'], $date);
    $exec = $wpdb->query($sql);
    
    $product_url = get_permalink($productId);
    wp_die(json_encode(array('success'=>true, 'productUrl'=>$product_url))); 

}
    