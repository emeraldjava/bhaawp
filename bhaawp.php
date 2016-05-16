<?php
/*
Plugin Name:        BHAA Plugin
Plugin URI:         https://github.com/emeraldjava/bhaawp
Description:        Plugin for the Business House Athletic Association which handle user registration, race  results and leagues.
Version:            2016.05.16
Author:             paul.t.oconnell@gmail.com
Author URI:         https://github.com/emeraldjava
Text Domain:        bhaawp
License:            GPL-2.0+
License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:        /languages
GitHub Plugin URI:  https://github.com/emeraldjava/bhaawp
GitHub Branch:      master
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}


/**
 * https://gilbert.pellegrom.me/using-psr-4-autoloading-in-wordpress-plugins/
 * http://torquemag.io/2015/01/using-class-autoloader-improve-wordpress-development/
 */
require_once('vendor/autoload.php');
define( 'BHAA_PLUGIN_DIR' , dirname(__FILE__) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
*----------------------------------------------------------------------------*/

//require_once( plugin_dir_path( __FILE__ ) . 'public/class-bhaa.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
* When the plugin is deleted, the uninstall.php file is loaded.
*/
register_activation_hook( __FILE__, array( 'Bhaa', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Bhaa', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Bhaa', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
*----------------------------------------------------------------------------*/

/*
 * Load the admin
*/
if ( is_admin() ) { //&& ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
  //require_once( plugin_dir_path( __FILE__ ) . 'admin/class-bhaa-admin.php' );
  add_action( 'plugins_loaded', array( 'Bhaa_Admin', 'get_instance' ) );
}
?>
