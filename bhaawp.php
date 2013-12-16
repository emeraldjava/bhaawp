<?php
/*
* Plugin Name: BHAA Plugin
* Plugin URI: https://github.com/emeraldjava/bhaawp
* Description: Plugin for the Business House Athletic Association which handle user registration, race results and leagues
* Version:           0.0.1
* Author: paul.t.oconnell@gmail.com
* Author URI: https://github.com/emeraldjava
* Text Domain:       bhaawp
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Domain Path:       /languages
* GitHub Plugin URI: https://github.com/emeraldjava/bhaawp
* GitHub Branch:     master
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
*----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-bhaa.php' );

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
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-bhaa-admin.php' );
	add_action( 'plugins_loaded', array( 'Bhaa_Admin', 'get_instance' ) );
}


class BHAA {
	
	var $connection;

	var $event;
	var $race;
	var $individualResultTable;
		
	var $house;
	var $runner;
	var $standardCalculator;

	var $registration;
	var $raceday;
	
	var $plugin_file;
	var $plugin_basename;

	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
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

		//$this->plugin_file = __FILE__;
		//$this->plugin_basename = plugin_basename( $this->plugin_file );
		//add_action( 'admin_menu', array( $this, 'add_page' ) );
		//add_action( 'network_admin_menu', array( $this, 'add_page' ) );
		
		//define( 'WP_GITHUB_FORCE_UPDATE', true );
				
		if ( is_admin() ) {
			new BhaaAdmin();
		}

		// init, wp_head, wp_enqueue_scripts
		add_action('init', array($this,'enqueue_scripts_and_style'));

		add_filter('the_content', array($this,'bhaa_content'));
		//add_action('init',array($this,'bhaa_form_actions'));

	}
	
	/*
	function add_page() {
		if ( current_user_can ( 'manage_options' ) ) {
			$this->options_page_hookname = add_plugins_page ( __( 'Github Updates', 'github_plugin_updater' ), __( 'Github Updates', 'github_plugin_updater' ), 'manage_options', 'github-updater', array( $this, 'admin_page' ) );
			add_filter( "network_admin_plugin_action_links_{$this->plugin_basename}", array( $this, 'filter_plugin_actions' ) );
			add_filter( "plugin_action_links_{$this->plugin_basename}", array( $this, 'filter_plugin_actions' ) );
		}
	}
	
	function filter_plugin_actions( $links ) {
		$settings_link = '<a href="plugins.php?page=github-updater">' . __( 'Setup', 'github_plugin_updater' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
	*/

	function bhaa_form_actions() {
		if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,17) == Raceday::BHAA_RACEDAY_FORM ) {
			error_log("action ".$_REQUEST['action']);
			error_log("name   ".$_REQUEST['name']);
			echo '<div class="thanks">THANKS</div>';
		}
	}

	/**
	 * The runner and raceday pages will be server from the plugin templates directory.
	 */
	function bhaa_content($page_content) {
		global $post;//, $wpdb, $wp_query;

		//if( empty($post) )
			//return $page_content;

		//error_log("bhaa_content ".$post->ID);
		// realex 3143
		if( $post->ID == 3143){
			$realex = new Realex();
			return $realex->process();
		} 
		//else if($post->ID==2015) {
			// runner page 2015
		//}
/* 		if( in_array($post->ID, array(3091)) ) {//2025,2937,2940
			error_log("bhaa_content ".$post->ID);
			ob_start();
			if( $post->ID == 3091) {//2025) {		// runner
				$this->bhaa_locate_template('leaguetable.php', true);// array('args'=>$args));
			} else if( $post->ID == 2025) {//2025) {		// runner
				$this->bhaa_locate_template('runner.php', true);// array('args'=>$args));
			} else if( $post->ID == 2937) {	// raceday
				$this->bhaa_locate_template('raceday.php', true);//, array('args'=>$args));
			}
			$page_content = ob_get_clean();
		} */
		else
			return $page_content;
	}

	function bhaa_locate_template( $template_name, $load=false, $args = array() ) {
		//First we check if there are overriding tempates in the child or parent theme

		$located = locate_template(array('plugins/bhaawp-master/'.$template_name));
		if( !$located ) {
			if ( file_exists(BHAA_PLUGIN_DIR.'/templates/'.$template_name) ) {

				$located = BHAA_PLUGIN_DIR.'/templates/'.$template_name;
			}

		}
		$located = apply_filters('bhaa_locate_template', $located, $template_name, $load, $args);

		if( $located && $load ) {
			if( is_array($args) )
				extract($args);
			include($located);
		}
		return $located;
	}

	/**
	 * http://codex.wordpress.org/Function_Reference/load_template
	 * http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
	 * https://github.com/stephenharris/Event-Organiser/blob/1.7.3/includes/event-organiser-templates.php#L193
	 */
	/* 	function bhaa_locate_template($template_names, $load = false, $require_once = true ) {
	$located = '';

	$template_dir = get_stylesheet_directory(); //child theme
	$parent_template_dir = get_template_directory(); //parent theme
	$stack = array( $template_dir, $parent_template_dir, BHAA_PLUGIN_DIR . 'templates' );
	foreach ( (array) $template_names as $template_name ) {
	if ( !$template_name )
		continue;
	foreach ( $stack as $template_stack ){
	if ( file_exists( trailingslashit( $template_stack ) . $template_name ) ) {
	$located = trailingslashit( $template_stack ) . $template_name;
	break;
	}
	}
	}

	if ( $load && '' != $located )
		load_template( $located, $require_once );
	return $located;
	} */


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
		new BHAAEventManager();

		// table views
		$this->individualResultTable = new RaceResultTable();

		$this->runner = new Runner();
		$this->event = new Event();
		$this->registration = new Registration();
		$this->raceday = new Raceday();

		$this->standardCalculator = new StandardCalculator();
		add_shortcode('eventStandardTable', array($this->standardCalculator,'eventStandardTable'));

		$runnerSearchWidget = new RunnerSearchWidget();
		add_action('widgets_init', array($runnerSearchWidget,'register'));
	}

	function getRaceday() {
		return $this->raceday;
	}

	function getRunner() {
		return $this->runner;
	}

	function getRaceResult() {
		return new RaceResult();
	}

	function getIndividualResultTable()	{
		return $this->individualResultTable;
	}

	public function getRaceTeamResultTable($race) {
		$teamResult = new TeamResult($race);
		return $teamResult->getRaceTeamResultTable();
	}
	
	public function getTeamResultsForHouse($house){
		$teamResult = new TeamResult($race);
		$results = $teamResult->getHouseResults($house);
		//var_dump($results,true);
		
		$options =  array('extension' => '.html');
		$mustache = new Mustache_Engine(
			array(
				'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates',$options),
				'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates/partials',$options)
			)
		);
		
		$template = $mustache->loadTemplate('team-results');
		return $template->render(
			array(
				'results' => $results
		));
	}

	/**
	 * Register bhaa wp jquery rules and css style.
	 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 * http://stackoverflow.com/questions/5790820/using-jquery-ui-dialog-in-wordpress
	 * http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/
	 */
	function enqueue_scripts_and_style() {
		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		//wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );
		//wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		// http://wordpress.stackexchange.com/questions/56343/template-issues-getting-ajax-search-results/56349#56349
		wp_register_script(
		'bhaawp',
		plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
		array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog'));

		wp_enqueue_script('bhaawp');
		// 		wp_register_script(
		// 		'bootstrap-js',
	 //		plugins_url('assets/js/bootstrap.min.js',__FILE__),
		//	array('jquery'));
		wp_enqueue_script('bootstrap-js');
		wp_localize_script(
			'bhaawp',
			'bhaaAjax',
			array('ajaxurl'=>admin_url('admin-ajax.php')));

		// register ajax methods
		//add_action('wp_ajax_nopriv_bhaawp_house_search',array($this,'bhaawp_house_search'));
		//add_action('wp_ajax_bhaawp_house_search',array($this,'bhaawp_house_search'));
		add_action('wp_ajax_nopriv_bhaawp_runner_search',array($this->runner,'bhaa_runner_search'));
		add_action('wp_ajax_bhaawp_runner_search',array($this->runner,'bhaa_runner_search'));

		// http://stackoverflow.com/questions/8849684/wordpress-jquery-ui-css-files
		// css style
		//wp_enqueue_style(
		//'jquery-bhaa-style',
		//'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style(
			'bhaawp',
			plugins_url() . '/bhaawp-master/assets/css/bhaawp.css',
			false);
		wp_enqueue_style(
			'jquery-bhaa-style',
			plugins_url() . '/bhaawp-master/assets/css/jqueryui/jquery-ui-1.10.3.custom.min.css',
			false);
		/*wp_enqueue_style(
	 		'bootstrap-css',
	 		plugins_url() . '/bhaawp-master/assets/css/bootstrap.min.css',
	 		false); */
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

	function defineConstants() {
		define('BHAAWP_PATH', plugin_dir_path(__FILE__));
		global $wpdb;
		$wpdb->raceresult 	= $wpdb->prefix.'bhaa_raceresult';
		$wpdb->teamresult 	= $wpdb->prefix.'bhaa_teamresult';
		$wpdb->importTable = $wpdb->prefix.'bhaa_import';
		$wpdb->standardTable = $wpdb->prefix.'bhaa_standard';
	}
}

// Run the Plugin
// TODO this reference should not be global, make private once all template references are removed
$BHAA = BHAA::get_instance();
?>