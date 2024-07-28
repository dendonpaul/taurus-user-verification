<?php

/**
 * Plugin Name: Taurus User Verification
 * Description: This plugin is used to verify a user email by sending a verification link.
 * Version: 1.0
 * Author: Denny Paul
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


if (!class_exists('Taurus_User_Verfication')) {
    class Taurus_User_Verification
    {
        function __construct()
        {
            $this->define_constants();

            //include and instantiate send verification class
            require TAURUS_VERIFY_PATH . '/includes/class.taurus-send-verification.php';
            $send_verification = new Taurus_Send_Verify_Verification();

            //Includes settings page in admin
            require TAURUS_VERIFY_PATH . '/admin/class.taurus-verify-register-settings.php';
            $taurus_verify_register_settings = new Taurus_Verify_Register_Settings();
        }

        //Define constants
        public function define_constants()
        {
            define('TAURUS_VERIFY_PATH', plugin_dir_path(__FILE__));
            define('TAURUS_VERIFY_URL', plugin_dir_url(__FILE__));
            define('TAURUS_VERIFY_VERSION', '1.0.0');
        }

        //Activation function
        public static function activate()
        {
            //Flush rewrite rules
            update_option('rewrite_rules', '');
        }

        //Deactivation function
        public static function deactivate()
        {
            //Flush rewrite rule
            flush_rewrite_rules();
        }

        //Uninstall function
        public static function uninstall()
        {
            // remove data when uninstalling plugin
        }
    }
}

if (class_exists('Taurus_User_Verification')) {
    register_activation_hook(__FILE__, array('Taurus_User_Verification', 'activate'));
    register_deactivation_hook(__FILE__, array('Taurus_User_Verification', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Taurus_User_Verification', 'uninstall'));

    $taurusUserVerify = new Taurus_User_Verification();
}
