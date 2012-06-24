<?php
/*
Plugin Name: BHAA wordpress plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 2012.06.24
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

class BhaaLoader
{
	var $version = '2012.06.24';
	
	var $admin;
	var $company;
	var $event;
	var $race;
	var $raceresult;
	var $runner;
	
	function BhaaLoader()
	{
		$this->__construct();
	}
	
	function __construct()
	{
		global $wpdb;
		$wpdb->show_errors();
		//$this->loadOptions();
		$this->defineConstants();
		//$this->loadTextdomain();
		$this->loadLibraries();
	
		register_activation_hook(__FILE__, array(&$this, 'activate') );
			
		//if (function_exists('register_uninstall_hook'))
			//register_uninstall_hook(__FILE__, array(&$this, 'uninstall'));
	
		add_action( 'widgets_init', array(&$this, 'registerWidget') );
		// Start this plugin once all other plugins are fully loaded
		add_action( 'plugins_loaded', array(&$this, 'initialize') );
	
		if ( is_admin() )
		{
			require_once (dirname (__FILE__) . '/admin/admin.php');
			$this->admin = new BhaaAdmin();
		}
	}
	
	function initialize()
	{
		// Add the script and style files
		add_action('wp_head', array(&$this, 'loadScripts') );
		//add_action('wp_print_styles', array(&$this, 'loadStyles') );
	}
		
	function registerWidget()
	{
		//register_widget('LeagueManagerWidget');
	}
	
	function defineConstants()
	{
		if ( !defined( 'WP_CONTENT_URL' ) )
			define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
		if ( !defined( 'WP_PLUGIN_URL' ) )
			define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
		if ( !defined( 'WP_CONTENT_DIR' ) )
			define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		if ( !defined( 'WP_PLUGIN_DIR' ) )
			define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
			
		//define( 'LEAGUEMANAGER_VERSION', $this->version );
		//define( 'LEAGUEMANAGER_DBVERSION', $this->dbversion );
		define( 'BHAAWP_URL', WP_PLUGIN_URL.'/bhaawp' );
		define( 'BHAAWP_PATH', WP_PLUGIN_DIR.'/bhaawp' );
	}
	
	function loadLibraries()
	{
		global $bhaaAJAX;
		
		// classes
		require_once (dirname (__FILE__) . '/classes/base.class.php');
		require_once (dirname (__FILE__) . '/classes/company.class.php');
		$this->company = new Company();
		require_once (dirname (__FILE__) . '/classes/event.class.php');
		$this->event = new Event();
		require_once (dirname (__FILE__) . '/classes/runner.class.php');
		$this->runner = new Runner();
		require_once (dirname (__FILE__) . '/classes/race.class.php');
		$this->race = new Race();
		require_once (dirname (__FILE__) . '/classes/raceresult.class.php');
		$this->raceresult = new RaceResult();
				
		// Global libraries
		//require_once (dirname (__FILE__) . '/lib/core.php');
		require_once (dirname (__FILE__) . '/lib/ajax.php');
		//require_once (dirname (__FILE__) . '/lib/stats.php');
		//require_once (dirname (__FILE__) . '/lib/shortcodes.php');
		//require_once (dirname (__FILE__) . '/lib/widget.php');
		//require_once (dirname (__FILE__) . '/functions.php');
		//require_once (dirname (__FILE__) . '/lib/championship.php');
		
		//$this->loadSports();
		$bhaaAjax = new BhaaAjax();
		

		$this->addShortCodes();
	}
	
	function loadScripts()
	{}
	
	function loadStyle()
	{}
	
	function addShortCodes()
	{
		add_shortcode( 'bhaa_companies', array($this->company,'listCompanies'));
		add_shortcode( 'bhaa_events', array($this->event,'listEvents'));
	}
	
	function activate()
	{
		$options = array();
		add_option( 'bhaa', $options, 'BHAA Options', 'yes' );
		add_option( 'bhaa_widget', array(), 'BHAA Widget Options', 'yes' );
		//$this->install();
	}
		
	function install()
	{
		//global $wpdb;
		//include_once( ABSPATH.'/wp-admin/includes/upgrade.php' );
		
		$this->company->createTable();
		$this->event->createTable();
		$this->race->createTable();
		$this->raceresult->createTable();
		$this->runner->createTable();
	}
	
	function uninstall()
	{
		global $wpdb;
		
		$delete_event_sql = "DROP TABLE ".$wpdb->prefix."bhaa_event;";
		$wpdb->query($delete_event_sql);
		
		$delete_runner_sql = "DROP TABLE ".$wpdb->prefix."bhaa_runner;";
		$wpdb->query($delete_runner_sql);
		
		//require_once(ABSPATH .’wp-admin/includes/upgrade.php’);
		dbDelta($delete_event_sql);
		dbDelta($delete_runner_sql);
		
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

?>