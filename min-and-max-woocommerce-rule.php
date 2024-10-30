<?php
/**
* Plugin Name: Min and max woocommerce Rule
* Description: Min and max woocommerce Purchase Rule can be give base on product category to all  user role wise
* Version: 2.1
* Tested up to: 4.9.8
* Author: Ajay Radadiya
* License: A "GNUGPLv3" license name 
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
  die('-1');
}

if (!defined('MAMWR_PLUGIN_NAME')) {
  define('MAMWR_PLUGIN_NAME', 'Min and max woocommerce Rule');
}
if (!defined('MAMWR_PLUGIN_VERSION')) {
  define('MAMWR_PLUGIN_VERSION', '2.1');
}
if (!defined('MAMWR_PLUGIN_FILE')) {
  define('MAMWR_PLUGIN_FILE', __FILE__);
}
if (!defined('MAMWR_PLUGIN_DIR')) {
  define('MAMWR_PLUGIN_DIR',plugins_url('', __FILE__));
}

if (!defined('MAMWR_DOMAIN')) {
  define('MAMWR_DOMAIN', 'mamwr');
}

//Main class
//Load required js,css and other files

if (!class_exists('MAMWR')) {

  class MAMWR {

    protected static $MAMWR_instance;

           /**
       * Constructor.
       *
       * @version 3.2.3
       */
      function __construct() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        //check plugin activted or not
        add_action('admin_init', array($this, 'MAMWR_check_plugin_state'));
      }

    //Add JS and CSS on Backend
    function MAMWR_load_admin_script_style() {
      wp_enqueue_style( 'mamwr_admin_css', MAMWR_PLUGIN_DIR . '/assets/css/mamwr-admin.css', false, '1.0.0' );
      wp_enqueue_script( 'mamwr_admin_js', MAMWR_PLUGIN_DIR . '/assets/js/mamwr-admin_js.js', false, '1.0.0' );
    }

    function MAMWR_show_notice() {

        if ( get_transient( get_current_user_id() . 'mamwrerror' ) ) {

          deactivate_plugins( plugin_basename( __FILE__ ) );

          delete_transient( get_current_user_id() . 'mamwrerror' );

          echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';

        }

    }

    function MAMWR_check_plugin_state(){
      if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        set_transient( get_current_user_id() . 'mamwrerror', 'message' );
      }
    }

    function init() {
      add_action( 'admin_notices', array($this, 'MAMWR_show_notice'));
       add_action('admin_enqueue_scripts', array($this, 'MAMWR_load_admin_script_style'));
    }

    //Load all includes files
    function includes() {

      //admin settings
      include_once('includes/mamwr-adminsettings.php');

      //Total Cart QTY Validation
      include_once('includes/mamwr-functionality.php');

      //single,variations,category etc product setting
      include_once('includes/mamwr-product_cat_settings.php');
    }

    //Plugin Rating
    public static function MAMWR_do_activation() {
      set_transient('mamwr-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function MAMWR_instance() {
      if (!isset(self::$MAMWR_instance)) {
        self::$MAMWR_instance = new self();
        self::$MAMWR_instance->init();
        self::$MAMWR_instance->includes();

      }
      return self::$MAMWR_instance;
    }

  }

  add_action('plugins_loaded', array('MAMWR', 'MAMWR_instance'));

  register_activation_hook(MAMWR_PLUGIN_FILE, array('MAMWR', 'MAMWR_do_activation'));
}
