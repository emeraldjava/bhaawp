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
	
	
	private function __construct() {
		//global $wpdb;
		//$wpdb->show_errors();
		//$this->loadOptions();
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
}

// Run the Plugin
// TODO this reference should not be global, make private once all template references are removed
$BHAA = BHAA::get_instance();
?>