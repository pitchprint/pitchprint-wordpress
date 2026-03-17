<?php

namespace pitchprint\functions\admin;

function ppa_create_settings() {
    echo '<p>' . __("You can generate your API and secret keys from the <a target=\"_blank\" href=\"https://admin.pitchprint.com/domains\">PitchPrint domains page</a>", "PitchPrint") . '</p>';
}

function settings_api_init() {
    add_settings_section(
        'ppa_settings_section',
        __('PitchPrint Settings', 'PitchPrint'),
        'pitchprint\\functions\\admin\\ppa_create_settings',
        'pitchprint'
    );

    // Add settings fields
    add_settings_field(
        'ppa_api_key',
        __('API Key', 'PitchPrint'),
        'pitchprint\\functions\\admin\\ppa_api_key',
        'pitchprint',
        'ppa_settings_section'
    );
    add_settings_field(
        'ppa_secret_key',
        __('Secret Key', 'PitchPrint'),
        'pitchprint\\functions\\admin\\ppa_secret_key',
        'pitchprint',
        'ppa_settings_section'
    );
    add_settings_field(
        'ppa_cat_customize',
        __('Category Customization', 'PitchPrint'),
        'pitchprint\\functions\\admin\\ppa_cat_customize',
        'pitchprint',
        'ppa_settings_section'
    );
    add_settings_field(
        'ppa_email_download_link',
        __('Include PDF Link in Customer Email', 'PitchPrint'),
        'pitchprint\\functions\\admin\\ppa_email_download_link',
        'pitchprint',
        'ppa_settings_section'
    );
    add_settings_field(
        'ppa_disable_session',
        __('Disable Session Backup', 'PitchPrint'),
        'pitchprint\\functions\\admin\\ppa_disable_session',
        'pitchprint',
        'ppa_settings_section'
    );

    // Register settings with sanitization callbacks
    register_setting('pitchprint', 'ppa_api_key', [
        'sanitize_callback' => function($value) {
            return trim($value);
        }
    ]);
    register_setting('pitchprint', 'ppa_secret_key', [
        'sanitize_callback' => function($value) {
            return trim($value);
        }
    ]);
    register_setting('pitchprint', 'ppa_cat_customize', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    register_setting('pitchprint', 'ppa_email_download_link', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    register_setting('pitchprint', 'ppa_disable_session', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
}

function ppa_api_key() {
    $value = esc_attr(get_option('ppa_api_key'));
    echo '<input class="regular-text" id="ppa_api_key" name="ppa_api_key" type="text" value="' . $value . '" />';
}

function ppa_secret_key() {
    $value = esc_attr(get_option('ppa_secret_key'));
    echo '<input class="regular-text" id="ppa_secret_key" name="ppa_secret_key" type="text" value="' . $value . '" />';
}

function ppa_cat_customize() {
    $checked = checked(get_option('ppa_cat_customize'), 'on', false);
    echo '<input id="ppa_cat_customize" name="ppa_cat_customize" type="checkbox" ' . $checked . ' />';
    echo '<label for="ppa_cat_customize">' . __('Show the Customize Button in Product Category', 'PitchPrint') . '</label>';
}

function ppa_email_download_link() {
    $checked = checked(get_option('ppa_email_download_link'), 'on', false);
    echo '<input id="ppa_email_download_link" name="ppa_email_download_link" type="checkbox" ' . $checked . ' />';
    echo '<label for="ppa_email_download_link">' . __('Include PDF Link in Customer Email', 'PitchPrint') . '</label>';
}

function ppa_disable_session() {
    $checked = checked(get_option('ppa_disable_session'), 'on', false);
    echo '<input id="ppa_disable_session" name="ppa_disable_session" type="checkbox" ' . $checked . ' />';
    echo '<label for="ppa_disable_session">' . __('Disable PHP session backup (enable this if your host uses Batcache or similar page caching)', 'PitchPrint') . '</label>';
}

function add_settings_link($links) {
    $settings_link = [
        '<a href="/wp-admin/admin.php?page=pitchprint" target="_blank" rel="noopener">' . __('Settings', 'PitchPrint') . '</a>',
        '<a href="https://docs.pitchprint.com/" target="_blank" rel="noopener">' . __('Documentation', 'PitchPrint') . '</a>',
        '<a href="https://admin.pitchprint.com/dashboard" target="_blank" rel="noopener">' . __('Admin Dashboard & Support', 'PitchPrint') . '</a>'
    ];
    return array_merge($links, $settings_link);
}