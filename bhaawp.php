<?php
/*
Plugin Name: BHAA wordpress plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 2012.09.24
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

class BhaaLoader
{
	var $version = '2012.09.24';
	
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
		add_action('init', array($this,'enqueue_scripts_and_style'));
		//add_filter('pre_get_posts', array($this,'tgm_cpt_search'));
	}

	/**
	 * ajax
	 * http://thomasgriffinmedia.com/blog/2010/11/how-to-include-custom-post-types-in-wordpress-search-results/
	 */
	function bhaa_cpt_search( $query ) {
		if ( $query->is_search )
			$query->set( 'post_type', array( 'company' ) );
		return $query;
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
		
		add_action('p2p_created_connection',array($this,'bhaa_p2p_created_connection'));
	}
		
	/**
	 * https://github.com/scribu/wp-posts-to-posts/issues/236
	 * @param unknown_type $p2p_id
	 */
	function bhaa_p2p_created_connection($p2p_id)
	{
		$connection = p2p_get_connection( $p2p_id );
// 		if ( 'some-ctype-name' == $connection->p2p_type ) {
// 			// do things
// 		}
		error_log('bhaa_p2p_created_connection() '.$connection->p2p_type);
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
		
		require_once (dirname (__FILE__) . '/widgets/RaceResult_Widget.php');
		
		// Global libraries
		//require_once (dirname (__FILE__) . '/lib/core.php');
		//require_once (dirname (__FILE__) . '/lib/ajax.php');
 		//$bhaaAjax = new BhaaAjax();		
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
		
		
 		wp_register_script('bhaawp', plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
 			array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog'));
 		wp_enqueue_script('bhaawp');
 		wp_localize_script('bhaawp','bhaawp',array('ajaxurl'=>admin_url('admin-ajax.php')));

 		// register ajax methods 
 		add_action('wp_ajax_nopriv_bhaawp_company_search',array($this,'bhaawp_company_search'));
		add_action('wp_ajax_bhaawp_company_search',array($this,'bhaawp_company_search'));
			
 		// css style 
 		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
 		error_log('load_scripts',0);
	}
	
	/**
	 * 
	 * http://wp.tutsplus.com/tutorials/theme-development/add-jquery-autocomplete-to-your-sites-search/
	 * 
	 * http://alex.leonard.ie/2010/10/19/resetting-your-wp_query/
	 * query args
	 * http://wpsnipp.com/index.php/template/create-multiple-search-templates-for-custom-post-types/
	 */
// 	FUNCTION BHAAWP_COMPANY_SEARCHXX() {
// 		// GET THE SUBMITTED PARAMETERS
// 		//$POSTID = $_POST['POSTID'];
	
// 		$ARGS = ARRAY(
// 			'POST_TYPE'=> 'COMPANY',
// 			'NAME'    => $_REQUEST['TERM']);
// 		ERROR_LOG('BHAAWP_COMPANY_SEARCH '.$_REQUEST['TERM']);
// //		QUERY_POSTS($ARGS);
		
// 		$COMPANYQUERY = NEW WP_QUERY($ARGS);//'PAGENAME=CONTACT' );
		
// 		ERROR_LOG('BHAAWP_COMPANY_SEARCH '.PRINTF($COMPANYQUERY->QUERY));
		
// 		$SUGGESTIONS=ARRAY();
// 		WHILE ( $COMPANYQUERY->HAVE_POSTS() ) : 
// 			$COMPANYQUERY->THE_POST();
// 			$SUGGESTION = ARRAY();
// 			$SUGGESTION['LABEL'] = GET_THE_TITLE();
// 			$SUGGESTION['LINK'] = GET_PERMALINK();
// 			$SUGGESTIONS[]= $SUGGESTION;
// 		ENDWHILE;
		
// // 		IF ($COMPANYQUERY->HAVE_POSTS()) : 
// // 			$SUGGESTION = ARRAY();
// // 			WHILE ($QUERY->HAVE_POSTS()) : 
// // 				$QUERY->THE_POST();
// // 				$SUGGESTION['LABEL'] = ESC_HTML($QUERY->THE_POST()->POST_TITLE);
// // 				$SUGGESTION['LINK'] = GET_PERMALINK();
// // 				$SUGGESTIONS[]= $SUGGESTION;
// // 		// DO LOOP STUFF
// // 			ENDWHILE;
// // 		ENDIF;
		
// 		// INITIALISE SUGGESTIONS ARRAY
// // 		$SUGGESTIONS=ARRAY();
// // 		GLOBAL $POST;
// // 		FOREACH ($QUERY->GET_POSTS() AS $POST): 
// // 			SETUP_POSTDATA($POST);
// // 			// INITIALISE SUGGESTION ARRAY
// // 			$SUGGESTION = ARRAY();
// // 			$SUGGESTION['LABEL'] = ESC_HTML($POST->POST_TITLE);
// // 			$SUGGESTION['LINK'] = GET_PERMALINK();
// // 			// ADD SUGGESTION TO SUGGESTIONS ARRAY
// // 			$SUGGESTIONS[]= $SUGGESTION;
// // 		ENDFOREACH;
		
// 		// GENERATE THE RESPONSE
// 		$RESPONSE = JSON_ENCODE( $SUGGESTIONS );//ARRAY( 'SUCCESS' => TRUE, 'POSTID'=>$POSTID ) );
// 		WP_RESET_POSTDATA();
		
// 		ERROR_LOG('BHAAWP_COMPANY_SEARCH '.$RESPONSE);
// 		// RESPONSE OUTPUT
// 		HEADER( "CONTENT-TYPE: APPLICATION/JSON" );
// 		ECHO $RESPONSE;
	
// 		// IMPORTANT: DON'T FORGET TO "EXIT"
// 		EXIT;
// 	}
	
	function bhaawp_company_search() {
		error_log('bhaawp_company_search '.$_REQUEST['term']);
		$posts = get_posts( array(
			's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
			'post_type' => 'company'
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
	
		$response = json_encode(array('matches'=>$suggestions));
		//error_log('bhaawp_company_search '.$response);
		echo $response;
		wp_reset_postdata();
		//die();
 		exit;
	}
	
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