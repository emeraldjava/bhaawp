<?php
class BhaaAdmin
{
	var $raceResult;
	var $import;
	var $raceAdmin;
		
	function BhaaAdmin()
	{
		require_once( ABSPATH . 'wp-admin/includes/template.php' );
				
		require_once (dirname (__FILE__) . '/race.admin.php');
		$this->raceAdmin = new RaceAdmin();
		
		require_once (dirname (__FILE__) . '/import.php');
		$this->import = new BhaaImport();
		
// 		add_action('admin_print_scripts', array(&$this, 'loadScripts') );
// 		add_action('admin_print_styles', array(&$this, 'loadStyles') );
	
		add_action('admin_init',array($this->raceAdmin,'init'));
 		//add_action( 'admin_menu', array(&$this, 'bhaa_admin_plugin_menu') );
	
// 		// Add meta box to post screen
// 		add_meta_box( 'leaguemanager', __('Match-Report','leaguemanager'), array(&$this, 'addMetaBox'), 'post' );
// 		add_action( 'publish_post', array(&$this, 'editMatchReport') );
// 		add_action( 'edit_post', array(&$this, 'editMatchReport') );
	
// 		add_action('wp_ajax_leaguemanager_get_season_dropdown', array(&$this, 'getSeasonDropdown'));
// 		add_action('wp_ajax_leaguemanager_get_match_dropdown', array(&$this, 'getMatchDropdown'));
	}
	
	function bhaa_admin_plugin_menu()
	{
		//add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'main'));
		
		//add_submenu_page('bhaa', 'BHAA', 'Menu', 'manage_options', 'main', array(&$this, 'main'));
		
		//add_submenu_page('bhaa' ,'BHAA','Race Results','manage_options', 'company' , array(&$this->raceResult,'table'));
		
		//add_submenu_page('bhaa' ,'BHAA','Help','manage_options', 'help' , array(&$this, 'help'));

		//add_submenu_page('bhaa' ,'BHAA','Import','manage_options', 'import' , array(&$this->import, 'dispatch'));
		
		//add_options_page( 'BHAA Plugin Options', 'BHAA Plugin', 'manage_options', 'my-unique-identifier', 'bhaa_plugin_options');
		
//		add_management_page(
	//		'bhaaimport', 'BhaaImport', __('Import bhaa details'), array ($import, 'dispatch'));
				
	//			'edit.php', 'CSV Importer', 'manage_options', __FILE__,
		//		array($plugin, 'form'));
	}
	
	function main()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>Main BHAA Admin Page.</p>';
		echo '</div>';
	}
	
	function help()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Admin Help Page.</p>';
		echo '</div>';
	}
	
	function bhaa_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>Here is where the form would go if I actually had options.</p>';
		echo '</div>';
	}
}