<?php
/**
 * Plugin Name: Size Chart WooCommerce
 * Description: This plugin allows you to Size chart in woocommerce.
 * Version: 1.0
 * Author: Ocean Infotech
 * Copyright: 2019 
**/


if (!defined('ABSPATH')) {
   die('-1');
}
if (!defined('OCSCW_PLUGIN_NAME')) {
   define('OCSCW_PLUGIN_NAME', 'Size Chart WooCommerce');
}
if (!defined('OCSCW_PLUGIN_VERSION')) {
   define('OCSCW_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCSCW_PLUGIN_FILE')) {
   define('OCSCW_PLUGIN_FILE', __FILE__);
}
if (!defined('OCSCW_PLUGIN_DIR')) {
   define('OCSCW_PLUGIN_DIR',plugins_url('', __FILE__));
}
if(!defined('OCSCW_PLUGIN_AB_PATH')) {
   define('OCSCW_PLUGIN_AB_PATH',plugin_dir_path( __FILE__ ));
}
if (!defined('OCSCW_DOMAIN')) {
   define('OCSCW_DOMAIN', 'OCSCW');
}
if (!defined('OCSCW_PREFIX')) {
   define('OCSCW_PREFIX', 'ocscw_');
}




if (!class_exists('OCSCW')) {
   class OCSCW {

      protected static $OCSCW_instance;
      function __construct() {
         include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
         add_action('admin_init', array($this, 'OCSCW_check_plugin_state'));
      }

    
      function OCSCW_check_plugin_state(){
         if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
            set_transient( get_current_user_id() . 'OCSCWerror', 'message' );
         }
      }


      function OCSCW_load_script_style() {
         wp_enqueue_style( 'OCSCW_front_style', OCSCW_PLUGIN_DIR . '/includes/css/ocscw_front_style.css', false, '1.0.0' );
         wp_enqueue_script( 'OCSCW_front_script', OCSCW_PLUGIN_DIR . '/includes/js/ocscw_front_script.js', false, '1.0.0' );

         wp_localize_script( 'OCSCW_front_script', 'ajax_url', admin_url('admin-ajax.php?action=popup_create') );
         $ocscw_img_array = OCSCW_PLUGIN_DIR;
         wp_localize_script( 'OCSCW_front_script', 'ocscw_object_name', $ocscw_img_array );
      }


      function OCSCW_load_admin_script_style() {
         wp_enqueue_style( 'OCSCW_admin_style', OCSCW_PLUGIN_DIR . '/includes/css/ocscw_back_style.css', false, '1.0.0' );
         wp_enqueue_script( 'OCSCW_admin_script', OCSCW_PLUGIN_DIR . '/includes/js/ocscw_back_script.js', array( 'jquery', 'select2') );
         $ocscw_img_array = OCSCW_PLUGIN_DIR;
         wp_localize_script( 'OCSCW_admin_script', 'ocscw_object_name', $ocscw_img_array );
      }


      function OCSCW_show_notice() {
         if ( get_transient( get_current_user_id() . 'OCSCWerror' ) ) {

            deactivate_plugins( plugin_basename( __FILE__ ) );

            delete_transient( get_current_user_id() . 'OCSCWerror' );

            echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';

         }
      }


      function OCSCW_create_post(){
         add_post(true);
      }
         

      function init() {
         add_action('admin_notices', array($this, 'OCSCW_show_notice'));
         add_action('admin_enqueue_scripts', array($this, 'OCSCW_load_admin_script_style'));
         add_action('wp_enqueue_scripts',  array($this, 'OCSCW_load_script_style'));
         add_action('init',  array($this, 'OCSCW_create_post'));
      }


      function includes() {
         include_once('admin/ocscw_create_post.php');
         include_once('admin/ocscw_product_mb.php');
         include_once('front/ocscw_front.php');
      }


      public static function OCSCW_instance() {
         if (!isset(self::$OCSCW_instance)) {
            self::$OCSCW_instance = new self();
            self::$OCSCW_instance->includes();
            self::$OCSCW_instance->init();
         }
         return self::$OCSCW_instance;
      }
   }
   add_action('plugins_loaded', array('OCSCW', 'OCSCW_instance'));
}


