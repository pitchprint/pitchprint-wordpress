<?php

    namespace pitchprint\functions\general;

    function set_cookie() {
        if (!isset($_COOKIE[PITCHPRINT_CUSTOMIZATION_KEY])) {
            $token = bin2hex(random_bytes(16));
            if (!headers_sent()) {
                setcookie(PITCHPRINT_CUSTOMIZATION_KEY, $token, time() + PITCHPRINT_CUSTOMIZATION_DURATION, '/');
            }
        }
    }

    function get_user_token() {
        if (isset($_COOKIE[PITCHPRINT_CUSTOMIZATION_KEY]))
            return $_COOKIE[PITCHPRINT_CUSTOMIZATION_KEY];
    
        // Generate a random token for the user (guest or signed-in)
        $token = bin2hex(random_bytes(16));
        if (!headers_sent()) {
            setcookie(PITCHPRINT_CUSTOMIZATION_KEY, $token, time() + PITCHPRINT_CUSTOMIZATION_DURATION, '/');
        }
        return $token;
    }

    function save_customization_data($product_id, $customization_data) {
        $user_token = get_user_token();
        $key = 'pitchprint_' . $user_token . '_' . $product_id;

        // Try saving to transient first
        delete_transient($key);
        $transient_result = set_transient($key, $customization_data, PITCHPRINT_CUSTOMIZATION_DURATION);

        // Also save to session as a backup
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$key] = $customization_data;

        // Return the key if transient save was successful, otherwise FALSE
        return $transient_result !== FALSE ? $key : FALSE;
    }

    function get_customization_data($product_id) {
        $user_token = get_user_token();
        $key = 'pitchprint_' . $user_token . '_' . $product_id;

        // Try getting from transient first
        $data = get_transient($key);

        if ($data !== false) {
            return $data;
        }

        // If transient failed or expired, try getting from session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION[$key])) {
            // Optionally, restore the transient if found in session
            // set_transient($key, $_SESSION[$key], PITCHPRINT_CUSTOMIZATION_DURATION);
            return $_SESSION[$key];
        }

        return false; // Return false if not found in either
    }

    function delete_customization_data($product_id) {
        $user_token = get_user_token();
        $key = 'pitchprint_' . $user_token . '_' . $product_id;

        // Delete from transient
        delete_transient($key);

        // Delete from session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

        return TRUE;
    }
