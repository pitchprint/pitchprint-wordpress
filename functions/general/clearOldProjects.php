<?php

namespace pitchprint\functions\general;

function clearOldProjects() {
    global $PitchPrint;
    global $wpdb;
    $sql = "DELETE FROM `". $PitchPrint->ppTable ."` WHERE `expires` < '" . date('Y-m-d H:i:s', time()) . "';";
    $wpdb->query($sql);
}
