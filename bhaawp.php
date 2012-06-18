<?php
/*
Plugin Name: BHAA wordpress plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 2012.06.18
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

class BhaaLoader
{
	var $version = '2012.06.18';
	
	var $admin;
	
	function BhaaLoader()
	{
		$this->__construct();
	}
	
	function __construct()
	{
		global $wpdb;
		$wpdb->show_errors();
		//$this->loadOptions();
		//$this->defineConstants();
		$this->defineTables();
		//$this->loadTextdomain();
		$this->loadLibraries();
	
		register_activation_hook(__FILE__, array(&$this, 'activate') );
			
		if (function_exists('register_uninstall_hook'))
			register_uninstall_hook(__FILE__, array(&$this, 'uninstall'));
	
		add_action( 'widgets_init', array(&$this, 'registerWidget') );
		// Start this plugin once all other plugins are fully loaded
		add_action( 'plugins_loaded', array(&$this, 'initialize') );
	
		if ( is_admin() )
			$this->admin = new BhaaAdmin();
	}
	
	function initialize()
	{
		// Add the script and style files
		add_action('wp_head', array(&$this, 'loadScripts') );
		add_action('wp_print_styles', array(&$this, 'loadStyles') );
	}
		
	function registerWidget()
	{
		//register_widget('LeagueManagerWidget');
	}
	
	function loadLibraries()
	{
		global $bhaaShortCodes, $bhaaAJAX;
		
		// Global libraries
		//require_once (dirname (__FILE__) . '/lib/core.php');
		require_once (dirname (__FILE__) . '/lib/ajax.php');
		//require_once (dirname (__FILE__) . '/lib/stats.php');
		require_once (dirname (__FILE__) . '/lib/shortcodes.php');
		//require_once (dirname (__FILE__) . '/lib/widget.php');
		//require_once (dirname (__FILE__) . '/functions.php');
		//require_once (dirname (__FILE__) . '/lib/championship.php');
		
		//$this->loadSports();
		$bhaaAjax = new BhaaAjax();
		
		if ( is_admin() ) {
			//require_once (dirname (__FILE__) . '/lib/image.php');
			require_once (dirname (__FILE__) . '/admin/admin.php');
		}
			
		$bhaaShortCodes = new BhaaShortCodes();
	}
	
	function loadScripts()
	{}
	
	function loadStyle()
	{}
	
	function activate()
	{
		$options = array();
		add_option( 'bhaa', $options, 'BHAA Options', 'yes' );
		add_option( 'bhaa_widget', array(), 'BHAA Widget Options', 'yes' );
		
		$this->install();
	}
	
	function defineTables()
	{
		global $wpdb;
		$wpdb->bhaa_event = $wpdb->prefix .'bhaa_event';
		$wpdb->bhaa_runner = $wpdb->prefix .'bhaa_runner';
	}
	
	function install()
	{
		global $wpdb;
		include_once( ABSPATH.'/wp-admin/includes/upgrade.php' );
		
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		
		$event_tablename = $wpdb->prefix."bhaa_event";
		$event_sql = "CREATE TABLE `$event_tablename` (
				`id` int(11) NOT NULL auto_increment,
				`name` varchar(40) NOT NULL,
				`tag` varchar(15) NOT NULL,
				`location` varchar(100) NOT NULL,
				`date` date NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=InnoDB $charset_collate;";
		dbDelta($event_sql);
		
		$runner_tablename = $wpdb->prefix . "bhaa_runner";
		$runner_sql = "CREATE TABLE `$runner_tablename` (
			`id` int(11) NOT NULL auto_increment,
			`firstname` varchar(40) NOT NULL,
			`surnamename` varchar(40) NOT NULL,
			`status` varchar(15) NOT NULL,
			`standard` varchar(100) NOT NULL,
			`dateofbirth` date NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB $charset_collate;";
		dbDelta($runner_sql);
		//$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );

	}
	
	function uninstall()
	{
		global $wpdb;
		
		$wpdb->query( "DROP TABLE $wpdb->bhaa_event" );
		$wpdb->query( "DROP TABLE $wpdb->bhaa_runner" );
		
		delete_option( 'bhaa_widget' );
		delete_option( 'bhaa' );
	}
	
	function getAdmin()
	{
		return $this->admin;
	}
	
}

// Run the Plugin
global $loader;
$loader = new BhaaLoader();

// $plugin_version = '1.0.3';
// define('BHAA_VERSION', $plugin_version);

// $plugin_name = 'bhaawp';
// $plugin_file = $plugin_name.'.php';
// $plugin_class = 'bhaa';
// $plugin_admin_class = 'bhaaadmin';
// $plugin_class_file = $plugin_name.'.class.php';
// $plugin_admin_class_file = $plugin_name.'admin.class.php';
// // define the plugin prefix we are going to use for naming all
// // classes, ids, actions etc... this is done to avoid conflicts with other plugins
// $plugin_prefix = $plugin_name.'_';
// $plugin_dir = get_bloginfo('wpurl').'/wp-content/plugins/bhaawp';

// // Include the class file
// if (!class_exists($plugin_class)) {
// 	include('/home/assure/bhaa/wordpress/wp-content/plugins/bhaawp/'.$plugin_class_file);
// 	if (is_admin()) {
// 		require_once(dirname(__FILE__).'/'.$plugin_admin_class_file);
// 		include( '/home/assure/bhaa/wordpress/wp-content/plugins/bhaawp/bhaa.admin.event.table.php');
// 	}
// }

// //Create a new instance of the class file
// if (class_exists($plugin_class)) {
// 	$bhaa_plugin = new $plugin_class();
// }

// //Create a new instance of the class file
// if (is_admin() && class_exists($plugin_admin_class)) {
// 	$bhaa_admin_plugin = new $plugin_admin_class();
// }

// //Setup actions, hooks and filters
// if(isset($bhaa_plugin)){

// 	// Activation function
// 	register_activation_hook(__FILE__, array(&$bhaa_plugin, 'activate'));

// 	/**
// 	 * Routing plugin actions to class file
// 	 */
// 	global $wp_query;

// 	add_shortcode('bhaa', array($bhaa_plugin, 'bhaa_shortcode'));

// 	if (is_admin()) {
// 		add_action('admin_menu', array($bhaa_admin_plugin, 'bhaa_admin_plugin_menu'));
// 		//add_action('admin_menu', array(new BHAA_Event_Table(), 'add_bhaa_event_menu_item'));
// 	}
// }

// /**
//  * Perform init actions
//  */
// function bhaa_init(){
// 	//Upgrade/Install Routine
// 	if( is_admin() && current_user_can('activate_plugins') ){
// 		if( BHAA_VERSION > get_option('bhaadb_version', 0) ){
// 			include( '/home/assure/bhaa/wordpress/wp-content/plugins/bhaawp/bhaa.install.php');
			
// 			//bhaa_install();
// 		}
// 	}
// }
// add_filter('init','bhaa_init',1);
?>