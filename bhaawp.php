<?php
/*
Plugin Name: BHAA wordpress plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 2012.07.23
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

class BhaaLoader
{
	var $version = '2012.08.09';
	
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
	
		add_action( 'widgets_init', array(&$this, 'registerWidget') );
		// Start this plugin once all other plugins are fully loaded
		add_action( 'plugins_loaded', array(&$this, 'initialize') );

		add_action( 'init', array(&$this,'register_cpt_company'));
		//add_action( 'init',  array(&$this,'humm'));
		//add_action( 'init',  array(&$this->race,'humm'));

		
		//add_action('parse_query',array($this,'parse_query'), 1);
		//add_filter('query_vars', array($this,'query_vars') );
		//add_action('parse_request', array(&$this,'my_plugin_parse_request'));
		if ( is_admin() )
		{
			register_activation_hook(__FILE__, array('BhaaLoader', 'activate') );
			register_uninstall_hook(__FILE__, array('BhaaLoader', 'uninstall'));
			
			require_once (dirname (__FILE__) . '/admin/admin.php');
			$this->admin = new BhaaAdmin();
			
			//require_once (dirname (__FILE__) . '/admin/admin.php');

		}
		else 
		{
			$this->addShortCodes();
		}		
	}
	


	public function humm()
	{
// 		$labels = array(
// 				'name' => _x( 'races', 'race' ),
// 				'singular_name' => _x( 'race', 'race' ),
// 				'add_new' => _x( 'Add New', 'race' ),
// 				'add_new_item' => _x( 'Add New race', 'race' ),
// 				'edit_item' => _x( 'Edit race', 'race' ),
// 				'new_item' => _x( 'New race', 'race' ),
// 				'view_item' => _x( 'View race', 'race' ),
// 				'search_items' => _x( 'Search races', 'race' ),
// 				'not_found' => _x( 'No races found', 'race' ),
// 				'not_found_in_trash' => _x( 'No races found in Trash', 'race' ),
// 				'parent_item_colon' => _x( 'Parent event:', 'event' ),
// 				'menu_name' => _x( 'races', 'race' ),
// 		);
		
// 		$args = array(
// 				'labels' => $labels,
// 				'hierarchical' => false,
// 				'description' => 'bhaa race post',
// 				'supports' => array( 'title', 'editor'),// 'custom-fields', 'page-attributes' ),
// 				'public' => true,
// 				'show_ui' => true,
// 				'show_in_menu' => true,
// 				'show_in_nav_menus' => true,
// 				'publicly_queryable' => true,
// 				'exclude_from_search' => false,
// 				'has_archive' => true,
// 				'query_var' => 'race',
// 				'can_export' => true,
// 				'rewrite' => array('slug' => 'race'),
// 				'capability_type' => 'post'
// 		);
//		register_post_type( 'race', $args );
		//register_post_type( 'race', $this->race->getCPT() );
		
	}
	
	/**
	 * http://www.voidtrance.net/2010/02/passing-and-receiving-query-variables/
	 * http://willnorris.com/2009/06/wordpress-plugin-pet-peeve-2-direct-calls-to-plugin-files
	 * @param unknown_type $wp
	 */
	function my_plugin_parse_request($wp) {
		// only process requests with "my-plugin=ajax-handler"
		
		// http://www.voidtrance.net/2010/02/passing-and-receiving-query-variables/
		if (array_key_exists ("event", $wp->query_vars))
		{
			$this->query["event"] = $wp->query_vars["event"];
			return $this->event->getEvent($this->query);
		}
	}
	
	static $queryvars = array(
		'event_id','race_id'
	);
	
	// Adding the id var so that WP recognizes it
	function query_vars($vars){
		$vars[] = 'bhaa_id';
		$vars[] = 'btype';
		return $vars;
	}
	
	/**
	 * Not the "WP way" but for now this'll do!
	 */
	function parse_query($wp){
		//global $wp_query, $wp_rewrite;
		if (array_key_exists ("bhaa_id", $wp->query_vars))
		{
			$this->query["bhaa_id"] = $wp->query_vars["bhaa_id"];
		}
		if (array_key_exists ("btype", $wp->query_vars))
		{
			$this->query["btype"] = $wp->query_vars["btype"];
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
	}
	
	function loadScripts()
	{}
	
	function loadStyle()
	{}
	
	function addShortCodes()
	{
		//add_shortcode( 'bhaa_companies', array($this->company,'listCompanies'));
		//add_shortcode( 'bhaa_events', array($this->event,'listEvents'));
		//add_shortcode( 'bhaa_event', array($this->event,'getEvent'));
		add_shortcode( 'bhaa', array($this,'bhaa_shortcode'));
	}
	
	public function bhaa_shortcode($attributes)
	{
		// Extract data
		extract(shortcode_atts(
				array(
						'id'      => 1,
						'type'    => 'default'
				),
				$attributes
		));
		$id = (int) $id;

// 		echo "wp_query ".var_dump($wp_query)."\n";
// 		echo "attributes ".var_dump($attributes)."\n";
		
// 		echo "type ".$type;
// 		echo "bhaa_id ".$this->query['bhaa_id'];
// 		echo "event_id A ".$this->query['event_id'];
// 		echo "event_id B ".$wp_query['event_id'];
		
// 		if (isset($wp_query->query_vars['event_id']))
// 		{
// 			print $wp_query->query_vars['event_id'];
// 		}
				
		// event shortcode with id?
		if ($type == 'event') 
		{
			if(isset($id))
				return $this->event->getEvent($id);
		}
		elseif($type == 'races')
		{
			// li
			return $this->race->listRaces($attributes);
		}
		elseif($type == 'raceresult')
		{
			// li
			return $this->raceresult->listRaceResult($attributes);
		}
		else
		{
			// default shortcode action
			return $this->event->listEvents($attributes);
		}		
	}
		
	function defineConstants()
	{
		define('BHAAWP_PATH', plugin_dir_path(__FILE__));
	
		global $wpdb;
	
		// tables
		$wpdb->event        = $wpdb->prefix.'bhaa_event';
		$wpdb->race   		= $wpdb->prefix.'bhaa_race';
		$wpdb->raceresult 	= $wpdb->prefix.'bhaa_raceresult';
		$wpdb->company    	= $wpdb->prefix.'bhaa_company';
	}
	
	public static function activate()
	{
		global $wpdb;
		
		$options = array();
		add_option( 'bhaa', $options, 'BHAA Options', 'yes' );
		add_option( 'bhaa_widget', array(), 'BHAA Widget Options', 'yes' );

		// company SQL
// 		$companySql = "id INT(11) NOT NULL auto_increment,
// 			name VARCHAR(100) NOT NULL,
// 			web VARCHAR(100),
// 			image VARCHAR(100),
// 			PRIMARY KEY  (id)";
// 		BhaaLoader::run_install_or_upgrade($wpdb->company,$companySql);
// 		$wpdb->insert( $wpdb->company,
// 			array( 'name' => 'BHAA', 'web' => 'http://www.bhaa.ie', 'image' => 'http://www.bhaa.ie' ) );
		
		// event SQL
// 		$eventSql = "id int(11) NOT NULL auto_increment,
// 			name varchar(40) NOT NULL,
// 			tag varchar(15) NOT NULL,
// 			location varchar(100) NOT NULL,
// 			date date NOT NULL,
// 			PRIMARY KEY (id)";
// 		BhaaLoader::run_install_or_upgrade($wpdb->event,$eventSql);
// 		$wpdb->insert( $wpdb->event,
// 				array( 'id' => '201001',
// 						'name'=>'South Dublin County Council',
// 						'tag'=>'sdcc2012',
// 						'location' => 'Tymon Park',
// 						'date' => '2010-01-05' ) );
// 		$wpdb->insert( $wpdb->event,
// 				array( 'id' => '201101',
// 						'name'=>'RTE',
// 						'tag'=>'rte2011',
// 						'location' => 'RTE',
// 						'date' => '2011-05-01' ) );
// 		$wpdb->insert( $wpdb->event,
// 				array( 'id' => '201205',
// 						'name'=>'KCLUB',
// 						'tag'=>'kclub2012',
// 						'location' => 'k-club',
// 						'date' => '2012-04-01' ) );
// 		$wpdb->insert( $wpdb->event,
// 				array( 'id' => '201210',
// 						'name'=>'DublinHalf',
// 						'tag'=>'dublinhalf2012',
// 						'location' => 'Park',
// 						'date' => '2012-07-01' ) );
		
		// race sql
// 		$raceSql = "id int(11) NOT NULL auto_increment,
// 			event varchar(40) NOT NULL,
// 			distance varchar(15) NOT NULL,
// 			unit enum('KM','Mile') DEFAULT 'KM',
// 			PRIMARY KEY  (`id`)";
// 		BhaaLoader::run_install_or_upgrade($wpdb->race,$raceSql);
// 		$wpdb->insert( $wpdb->race,
// 				array( 'id' => '201001',
// 						'event'=>'201001',
// 						'distance'=>'5',
// 						'unit'=>'KM') );
// 		$wpdb->insert( $wpdb->race,
// 				array( 'id' => '201102',
// 						'event'=>'201001',
// 						'distance'=>'8',
// 						'unit'=>'KM') );
// 		$wpdb->insert( $wpdb->race,
// 				array( 'id' => '201210',
// 						'event'=>'201205',
// 						'distance'=>'10',
// 						'unit'=>'KM') );
// 		$wpdb->insert( $wpdb->race,
// 				array( 'id' => '201220',
// 						'event'=>'201210',
// 						'distance'=>'9',
// 						'unit'=>'KM') );

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
		
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201001','runner'=>'7713','racetime'=>'00:50:00','number'=>'3'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201001','runner'=>'1000','racetime'=>'00:51:00','number'=>'1'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201001','runner'=>'2000','racetime'=>'00:54:00','number'=>'2'));
		
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201102','runner'=>'7713','racetime'=>'00:50:00','number'=>'13'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201102','runner'=>'1000','racetime'=>'00:51:00','number'=>'11'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201102','runner'=>'2000','racetime'=>'00:54:00','number'=>'12'));
		
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201210','runner'=>'7713','racetime'=>'00:50:00','number'=>'23'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201210','runner'=>'1000','racetime'=>'00:51:00','number'=>'21'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201210','runner'=>'2000','racetime'=>'00:54:00','number'=>'22'));
		
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201220','runner'=>'7713','racetime'=>'00:50:00','number'=>'33'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201220','runner'=>'1000','racetime'=>'00:51:00','number'=>'31'));
// 		$wpdb->insert( $wpdb->raceresult,array( 'race' => '201220','runner'=>'2000','racetime'=>'00:54:00','number'=>'32'));
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
				//$wpdb->event,
				$wpdb->raceresult
//				$wpdb->race,
	//			$wpdb->company
		);
		
		// Delete each table one by one
		foreach ($tables as $table)
		{
			$wpdb->query('DROP TABLE IF EXISTS '.$table.';');
		}
		
// 		$delete_event_sql = "DROP TABLE ".$wpdb->prefix."bhaa_event;";
// 		$wpdb->query($delete_event_sql);
		
// 		$delete_runner_sql = "DROP TABLE ".$wpdb->prefix."bhaa_runner;";
// 		$wpdb->query($delete_runner_sql);
		
// 		//require_once(ABSPATH .’wp-admin/includes/upgrade.php’);
// 		dbDelta($delete_event_sql);
// 		dbDelta($delete_runner_sql);
		
		delete_option( 'bhaa_widget' );
		delete_option( 'bhaa' );
	}
	
	function getAdmin()
	{
		return $this->admin;
	}
	
	// http://themergency.com/generators/wordpress-custom-post-types/
	function register_cpt_company() {
	
	    $labels = array( 
	        'name' => _x( 'Companies', 'company' ),
	        'singular_name' => _x( 'Company', 'company' ),
	        'add_new' => _x( 'Add New', 'company' ),
	        'add_new_item' => _x( 'Add New Company', 'company' ),
	        'edit_item' => _x( 'Edit Company', 'company' ),
	        'new_item' => _x( 'New Company', 'company' ),
	        'view_item' => _x( 'View Company', 'company' ),
	        'search_items' => _x( 'Search Companies', 'company' ),
	        'not_found' => _x( 'No companies found', 'company' ),
	        'not_found_in_trash' => _x( 'No companies found in Trash', 'company' ),
	        'parent_item_colon' => _x( 'Parent Company:', 'company' ),
	        'menu_name' => _x( 'Companies', 'company' ),
	    );
	
	    $args = array( 
	        'labels' => $labels,
	        'hierarchical' => false,
	        'description' => 'BHAA Company Details',
	        'supports' => array( 'title', 'editor', 'author', 'custom-fields', 'comments' ),
	        'taxonomies' => array( 'category', 'post_tag', 'sector' ),
	        'public' => true,
	        'show_ui' => true,
	        'show_in_menu' => true,
	        
	        
	        'show_in_nav_menus' => true,
	        'publicly_queryable' => true,
	        'exclude_from_search' => false,
	        'has_archive' => true,
	        'query_var' => true,
	        'can_export' => true,
	        'rewrite' => true,
	        'capability_type' => 'post'
	    );
	
	    register_post_type( 'company', $args );
	}
		
		
		
// 		function register_taxonomy_sector() {
		
// 			$labels = array(
// 					'name' => _x( 'sectors', 'sector' ),
// 					'singular_name' => _x( 'sector', 'sector' ),
// 					'search_items' => _x( 'Search sectors', 'sector' ),
// 					'popular_items' => _x( 'Popular sectors', 'sector' ),
// 					'all_items' => _x( 'All sectors', 'sector' ),
// 					'parent_item' => _x( 'Parent sector', 'sector' ),
// 					'parent_item_colon' => _x( 'Parent sector:', 'sector' ),
// 					'edit_item' => _x( 'Edit sector', 'sector' ),
// 					'update_item' => _x( 'Update sector', 'sector' ),
// 					'add_new_item' => _x( 'Add New sector', 'sector' ),
// 					'new_item_name' => _x( 'New sector', 'sector' ),
// 					'separate_items_with_commas' => _x( 'Separate sectors with commas', 'sector' ),
// 					'add_or_remove_items' => _x( 'Add or remove sectors', 'sector' ),
// 					'choose_from_most_used' => _x( 'Choose from most used sectors', 'sector' ),
// 					'menu_name' => _x( 'sectors', 'sector' ),
// 			);
		
// 			$args = array(
// 					'labels' => $labels,
// 					'public' => true,
// 					'show_in_nav_menus' => true,
// 					'show_ui' => true,
// 					'show_tagcloud' => true,
// 					'hierarchical' => false,
		
// 					'rewrite' => true,
// 					'query_var' => true
// 			);
		
// 			register_taxonomy( 'sector', array('post'), $args );
// 		}
}

// Run the Plugin
global $loader;
$loader = new BhaaLoader();
?>