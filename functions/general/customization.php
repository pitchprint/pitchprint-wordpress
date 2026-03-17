<?php

    namespace pitchprint\functions\general;

    function use_session_backup() {
        return get_option('ppa_disable_session') !== 'on';
    }

    function ensure_session() {
        if (use_session_backup() && session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    function get_user_token() {
        // For logged-in WordPress users, use their user ID as the token.
        // This ensures data isolation even if cookies are lost or shared.
        $user_id = get_current_user_id();
        if ($user_id > 0) {
            return 'user_' . $user_id;
        }

        // For guests, use the cookie token managed by the JS client
        if (isset($_COOKIE[PITCHPRINT_CUSTOMIZATION_KEY]) && !empty($_COOKIE[PITCHPRINT_CUSTOMIZATION_KEY])) {
            return sanitize_text_field($_COOKIE[PITCHPRINT_CUSTOMIZATION_KEY]);
        }

        return false;
    }

    function save_customization_data($product_id, $customization_data) {
        $product_id = absint($product_id);
        $customization_data = wp_unslash($customization_data);
        
        $user_token = get_user_token();
        if (!is_string($user_token) || empty($user_token)) {
            return false;
        }
        $key = 'pitchprint_' . $user_token . '_' . $product_id;

        delete_transient($key);
        $result = set_transient($key, $customization_data, PITCHPRINT_CUSTOMIZATION_DURATION);

        // Session backup for hosts that allow it
        ensure_session();
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[$key] = $customization_data;
        }

        return $result !== FALSE ? $key : FALSE;
    }

    function get_customization_data($product_id) {
        $user_token = get_user_token();
        if (!is_string($user_token) || empty($user_token)) {
            return false;
        }
        $key = 'pitchprint_' . $user_token . '_' . $product_id;

        $data = get_transient($key);
        if ($data !== false) {
            return $data;
        }

        // Fall back to session if transient expired
        ensure_session();
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return false;
    }

    function delete_customization_data($product_id) {
        $user_token = get_user_token();
        if (!is_string($user_token) || empty($user_token)) {
            return false;
        }
        $key = 'pitchprint_' . $user_token . '_' . $product_id;

        delete_transient($key);

        ensure_session();
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

        return TRUE;
    }
