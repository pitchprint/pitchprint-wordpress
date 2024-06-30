<?php

namespace pitchprint\functions\updates;

function db_product_id_medint_int() {
    global $wpdb;
    global $PitchPrint;

    $table_name = $PitchPrint->ppTable;

    // Change column type
    $wpdb->query("ALTER TABLE $table_name MODIFY COLUMN product_id INT");
}