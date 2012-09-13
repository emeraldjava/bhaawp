<?php
/*
Plugin Name: BHAA wordpress plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 2012.09.12
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

class BhaaLoader
{
	var $version = '2012.09.12';
	
	var $admin;
	
	var $company;
	var $race;
	var $league;
	
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
	
		//add_action( 'widgets_init', array(&$this, 'registerWidget') );
		// Start this plugin once all other plugins are fully loaded
		//add_action( 'plugins_loaded', array(&$this, 'initialize') );
		//add_action( 'init', array(&$this,'register_cpt_company'));
		add_action( 'wp_loaded', array(&$this,'bhaa_connection_types'));
		
		if ( is_admin() )
		{
			register_activation_hook(__FILE__, array('BhaaLoader', 'activate') );
			register_uninstall_hook(__FILE__, array('BhaaLoader', 'uninstall'));
			
			require_once (dirname (__FILE__) . '/admin/admin.php');
			$this->admin = new BhaaAdmin();
		}
		else 
		{
			$this->addShortCodes();
		}
		// init, wp_head, wp_enqueue_scripts
		add_action('init', array($this,'load_scripts'));
	}
		
	function bhaa_connection_types() {
		// Make sure the Posts 2 Posts plugin is active.
		require_once( ABSPATH . 'wp-content/plugins/posts-to-posts/core/api.php' );
		if ( !function_exists( 'p2p_register_connection_type' ) )
			return;
		
		p2p_register_connection_type( array(
				'name' => 'event_to_race',
				'from' => 'event',
				'to' => 'race',
				'cardinality' => 'one-to-many'
		));
		
		p2p_register_connection_type( array(
				'name' => 'league_to_event',
				'from' => 'league',
				'to' => 'event',
				'cardinality' => 'one-to-many'
		));
		
		p2p_register_connection_type( array(
				'name' => 'company_to_runner',
				'from' => 'company',
				'to' => 'user',
				'cardinality' => 'one-to-many'
		));
	}
		
	function initialize()
	{
		// Add the script and style files
		//add_action('wp_head', array(&$this, 'loadScripts') );
		//add_action('wp_enqueue_scripts',array(&$this,'loadScripts'));
		//add_action('wp_enqueue_scripts',array($this,'load_scripts'));
		//wp_enqueue_script('bhaawp', plugins_url('assets/js/bhaawp.jquery.js',__FILE__), 
		//	array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog')); //jQuery will load as dependency
		
		//add_action('wp_print_styles', array(&$this, 'loadStyles') );
	}
		
	function registerWidget()
	{
		//register_widget('LeagueManagerWidget');
	}
	
	function loadLibraries()
	{
		global $bhaaAJAX;
		
		// classes
		require_once (dirname (__FILE__) . '/classes/company.class.php');
		$this->company = new Company();
		require_once (dirname (__FILE__) . '/classes/race.class.php');
		$this->race = new Race();
		require_once (dirname (__FILE__) . '/classes/league.class.php');
		$this->league = new League();
		require_once (dirname (__FILE__) . '/classes/raceresult.class.php');
		require_once (dirname (__FILE__) . '/classes/raceresulttable.class.php');
		$this->raceresult = new RaceResult();
		require_once (dirname (__FILE__) . '/classes/runner.class.php');
		$this->runner = new Runner();
		
		// Global libraries
		//require_once (dirname (__FILE__) . '/lib/core.php');
		//require_once (dirname (__FILE__) . '/lib/ajax.php');
 		//$bhaaAjax = new BhaaAjax();		
	}
	
	/**
	 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 */
	function load_scripts()
	{
		
		//wp_deregister_script( 'jquery' );
		//wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		//wp_enqueue_script( 'jquery' );
		error_log('load_scripts '.plugins_url('assets/js/bhaawp.jquery.js',__FILE__),0);
		
//		wp_enqueue_script('bhaawp', plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
	//		array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog')); //jQuery will load as dependency
		
 		wp_register_script('bhaawp', plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
 			array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog'));
 		wp_enqueue_script('bhaawp');
 		error_log('load_scripts',0);
	}
	
	function loadStyle()
	{}
	
	function addShortCodes()
	{
		// TODO add short code link for racetec registration form.
		
		// register two short codes to have the runner class display the required form fields.
		add_shortcode( 'newrunnerform', array($this->runner,'new_runner_form'));
		add_shortcode( 'dayrunnerform', array($this->runner,'day_runner_form'));
	}
	
	public function bhaa_shortcode($attributes)
	{
		// Extract data
// 		extract(shortcode_atts(
// 				array(
// 						'id'      => 1,
// 						'type'    => 'default'
// 				),
// 				$attributes
// 		));
// 		$id = (int) $id;
				
// 		// event shortcode with id?
// 		if ($type == 'event') 
// 		{
// 			if(isset($id))
// 				return $this->event->getEvent($id);
// 		}
// 		elseif($type == 'races')
// 		{
// 			// li
// 			return $this->race->listRaces($attributes);
// 		}
// 		elseif($type == 'raceresult')
// 		{
// 			// li
// 			return $this->raceresult->listRaceResult($attributes);
// 		}
// 		else
// 		{
// 			// default shortcode action
// 			return $this->event->listEvents($attributes);
// 		}		
	}
		
	function defineConstants()
	{
		define('BHAAWP_PATH', plugin_dir_path(__FILE__));
		global $wpdb;
		$wpdb->raceresult 	= $wpdb->prefix.'bhaa_raceresult';
	}
	
	public static function activate()
	{
		global $wpdb;
		
		$options = array();
		add_option( 'bhaa', $options, 'BHAA Options', 'yes' );
		add_option( 'bhaa_widget', array(), 'BHAA Widget Options', 'yes' );

		// raceresult SQL
		$raceResultSql = "race int(11) NOT NULL,
			runner int(11) NOT NULL,
			racetime time,
			position int(11),
			racenumber int(11),
			category varchar(5),
			standard int(11),
			paceKM time,
			class varchar(25)";
		BhaaLoader::run_install_or_upgrade($wpdb->raceresult,$raceResultSql);
	}
	
	public static function run_install_or_upgrade($table_name, $sql)//, $db_version)
	{
		global $wpdb;
		// Table does not exist, we create it!
		// We use InnoDB and UTF-8 by default
		if ($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name)
		{
			$create = "CREATE TABLE ".$table_name." ( ".$sql." ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
			// We use the dbDelta method given by WP!
			require_once ABSPATH.'wp-admin/includes/upgrade.php';
			dbDelta($create);
		}
	}
	
	public static function uninstall()
	{	
		global $wpdb;
		
		// PHPLeague tables
		$tables = array(
			$wpdb->raceresult
		);
		
		// Delete each table one by one
		foreach ($tables as $table)
		{
			$wpdb->query('DROP TABLE IF EXISTS '.$table.';');
		}
			
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