<?php
/*
Plugin Name: BHAA Plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 2013.03.25
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

class BHAA
{
	var $version = '2013.03.25';
	
	//var $admin;
	var $connection;
	
	var $event;
	var $race;
	var $individualResultTable;
	var $teamResultTable;
			
	var $house;
//	var $league;
	var $runner;
	var $standardCalculator;
	
	var $registration;
	
	function __construct()
	{
		//global $wpdb;
		//$wpdb->show_errors();
		//$this->loadOptions();
		$this->defineConstants();
		//$this->loadTextdomain();
		$this->loadLibraries();
	
		//add_action( 'widgets_init', array(&$this, 'registerWidget') );
		// Start this plugin once all other plugins are fully loaded
		//add_action( 'plugins_loaded', array(&$this, 'initialize') );
		//add_action( 'p2p_init', array(&$this->connection,'bhaa_connection_types'));
		
		if ( is_admin() )
		{
			register_activation_hook(__FILE__, array($this, 'bhaa_activate') );
			register_uninstall_hook(__FILE__, array($this, 'bhaa_uninstall'));
			new BhaaAdmin();
		}

		// init, wp_head, wp_enqueue_scripts
		add_action('init', array($this,'enqueue_scripts_and_style'));
		//add_filter('pre_get_posts', array($this,'tgm_cpt_search'));
		
		// hook add_query_vars function into query_vars
		add_filter('query_vars', array($this,'add_query_vars'));
	}
	
	function add_query_vars($aVars) {
		$aVars[] = "division"; // represents the name of the product category as shown in the URL
		return $aVars;
	}

	/**
	 * 
	 * http://stackoverflow.com/questions/11833034/non-destructive-spl-autoload-register
	 * 
	 * add_action ( 'init' , 'class_loader' );

function class_loader () {
    // register an autoloader function for template classes
    spl_autoload_register ( 'template_autoloader' );
}

function template_autoloader ( $class ) {
if ( file_exists ( LG_FE_DIR . "/includes/chart_templates/class.{$class}.php" ) ) 
    include LG_FE_DIR . "/includes/chart_templates/class.{$class}.php";

}

	 */
	function loadLibraries()
	{
		require_once (dirname (__FILE__) . '/bootstrap.php');
		
		$this->connection = new Connection();
		
		new LeagueCpt();
		new RaceCpt();
		new HouseCpt();
		
		// table views
		$this->individualResultTable = new RaceResultTable();
		$this->teamResultTable = new TeamResultTable();
		
		$this->runner = new Runner();
		$this->event = new Event();
		$this->registration = new Registration();
		
		$this->standardCalculator = new StandardCalculator();
		add_shortcode('eventStandardTable', array($this->standardCalculator,'eventStandardTable'));
		
		$flickr = new BhaaFlickr();
		add_shortcode('bhaa_flickr',array($flickr,'bhaa_flickr_shortcode'));	
		
		add_shortcode('bhaa_registration', array($this->registration,'registration'));
		//require_once (dirname (__FILE__) . '/widgets/RaceResult_Widget.php');
		//$this->rrw = new RaceResult_Widget();
		$runnerSearchWidget = new RunnerSearchWidget();
		add_action('widgets_init', array($runnerSearchWidget,'register'));		
	}
	
	function getIndividualResultTable()
	{
		return $this->individualResultTable;
	}
	
	public function getTeamResultTable()
	{
		return $this->teamResultTable;
	}
	
	/**
	 * Register bhaa wp jquery rules and css style. 
	 * 
	 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 * http://stackoverflow.com/questions/5790820/using-jquery-ui-dialog-in-wordpress
	 * http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/
	 */
	function enqueue_scripts_and_style()
	{
		
		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		//wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );
		//wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		// http://wordpress.stackexchange.com/questions/56343/template-issues-getting-ajax-search-results/56349#56349
 		wp_register_script(
 			'bhaawp', 
 			plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
 			array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog'));
 		wp_enqueue_script('bhaawp');
 		wp_localize_script(
 			'bhaawp',
 			'bhaaAjax',
 			array('ajaxurl'=>admin_url('admin-ajax.php')));

 		// register ajax methods 
 		add_action('wp_ajax_nopriv_bhaawp_house_search',array($this,'bhaawp_house_search'));
		add_action('wp_ajax_bhaawp_house_search',array($this,'bhaawp_house_search'));
		add_action('wp_ajax_nopriv_bhaawp_runner_search',array($this->runner,'bhaa_runner_search'));
		add_action('wp_ajax_bhaawp_runner_search',array($this->runner,'bhaa_runner_search'));
		
 		// css style 
 		wp_enqueue_style('jquery-style','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
		
	function bhaawp_house_search() {
		error_log('bhaawp_house_search '.$_REQUEST['term']);
		$posts = get_posts( array(
			's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
			'post_type' => 'house'
		) );
		$suggestions=array();
	
		global $post;
		foreach ($posts as $post):
		setup_postdata($post);
		$suggestion = array();
		$suggestion['label'] = esc_html($post->post_title);
		$suggestion['link'] = get_permalink();
		$suggestions[]= $suggestion;
		endforeach;
	
		wp_reset_postdata();
		
		$response = json_encode(array('matches'=>$suggestions));
		//error_log('bhaawp_house_search '.$response);
		echo $response;
		
		die();
 		//exit;
	}
			
	function defineConstants()
	{
		define('BHAAWP_PATH', plugin_dir_path(__FILE__));
		global $wpdb;
		$wpdb->raceresult 	= $wpdb->prefix.'bhaa_raceresult';
		$wpdb->teamresult 	= $wpdb->prefix.'bhaa_teamresult';
		$wpdb->importTable = $wpdb->prefix.'bhaa_import';
		$wpdb->standardTable = $wpdb->prefix.'bhaa_standard';
	}
	
	function bhaa_activate()
	{
		global $wpdb;
		
		$options = array();
		add_option( 'bhaa', $options, 'BHAA Options', 'yes' );
		//add_option( 'bhaa_widget', array(), 'BHAA Widget Options', 'yes' );

		// raceresult SQL
		$raceResult = new RaceResult();
		$this->run_install_or_upgrade($raceResult->getName(),$raceResult->getCreateSQL());
		
		$teamResultSql = "
			id int(11) NOT NULL AUTO_INCREMENT,
			team int(11) NOT NULL,
			league int(11) NOT NULL,
			race int(11) NOT NULL,
			standardtotal int(11),
			positiontotal int(11),
			class enum('A', 'B', 'C', 'D', 'W', 'O', 'OW'),
			leaguepoints int(11),
			status enum('ACTIVE','PENDING'),
			PRIMARY KEY (id)";
		$this->run_install_or_upgrade($wpdb->teamresult,$teamResultSql);
		
		$leagueSummaryModel = new LeagueSummary();
		$this->run_install_or_upgrade($leagueSummaryModel->getName(),$leagueSummaryModel->getCreateSQL());
		
		$ageCategory = new AgeCategory();
		$this->run_install_or_upgrade($ageCategory->getName(),$ageCategory->getCreateSQL());
		
		$importTableSql = "
			id int(11) NOT NULL AUTO_INCREMENT,
			tag varchar(15) NOT NULL,
			type varchar(15) NOT NULL,
			new int(11) NOT NULL,
			old int(11) NOT NULL,
			PRIMARY KEY (id)";
		$this->run_install_or_upgrade($wpdb->importTable,$importTableSql);
		// populate the table with the data
		$this->run_install_or_upgrade($wpdb->standardTable,$this->standardCalculator->standardTableSql);
		foreach ($this->standardCalculator->standards as $i => $standard) {
			$wpdb->insert( $wpdb->standardTable, (array)$standard );
		}
	}
	
	function run_install_or_upgrade($table_name, $sql)//, $db_version)
	{
		global $wpdb;
		// Table does not exist, we create it!
		// We use InnoDB and UTF-8 by default
		if ($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name)
		{
			$create = "CREATE TABLE ".$table_name." ( ".$sql." ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			error_log($create);
			// We use the dbDelta method given by WP!
			require_once ABSPATH.'wp-admin/includes/upgrade.php';
			dbDelta($create);
		}
	}
	
	function bhaa_uninstall()
	{	
		global $wpdb;
		
		// tables
		$tables = array(
			//$wpdb->raceresult
		);
		
		// Delete each table one by one
		foreach ($tables as $table)
		{
			$wpdb->query('DROP TABLE IF EXISTS '.$table.';');
		}
			
		delete_option( 'bhaa_widget' );
		delete_option( 'bhaa' );
	}
}

// Run the Plugin
global $BHAA;
$BHAA = new BHAA();
?>