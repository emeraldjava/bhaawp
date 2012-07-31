<?php
class BhaaAdmin
{
	var $company;
	var $import;
	var $raceAdmin;
		
	function __construct()
	{
		
//		require_once('../../../wp-load.php');
		require_once( ABSPATH . 'wp-admin/includes/template.php' );
			
		require_once( ABSPATH . 'wp-admin/includes/import.php' );
		register_importer('bhaa', 'BHAA', __('BHAA Importer'), array (&$this,'import'));
		
		//require_once (dirname (__FILE__) . '/company.admin.class.php');
		//$this->company = new CompanyAdmin();
		
		require_once (dirname (__FILE__) . '/race.admin.php');
		$this->raceAdmin = new RaceAdmin();
		
		require_once (dirname (__FILE__) . '/import.php');
		$this->import = new BhaaImport();
		
// 		add_action('admin_print_scripts', array(&$this, 'loadScripts') );
// 		add_action('admin_print_styles', array(&$this, 'loadStyles') );
	
		add_action('admin_init',array($this->raceAdmin,'init'));
		
 		add_action( 'admin_menu', array(&$this, 'bhaa_admin_plugin_menu') );
	
// 		// Add meta box to post screen
// 		add_meta_box( 'leaguemanager', __('Match-Report','leaguemanager'), array(&$this, 'addMetaBox'), 'post' );
// 		add_action( 'publish_post', array(&$this, 'editMatchReport') );
// 		add_action( 'edit_post', array(&$this, 'editMatchReport') );
	
// 		add_action('wp_ajax_leaguemanager_get_season_dropdown', array(&$this, 'getSeasonDropdown'));
// 		add_action('wp_ajax_leaguemanager_get_match_dropdown', array(&$this, 'getMatchDropdown'));
	}
	
	function BhaaAdmin()
	{
		$this->__construct();
	}
	
	function bhaa_admin_plugin_menu()
	{
		add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'main'));
		
		add_submenu_page('bhaa', 'BHAA', 'Menu', 'manage_options', 'main', array(&$this, 'main'));
		
		//add_submenu_page('bhaa' ,'BHAA','Companies','manage_options', 'company' , array(&$this->company,'table'));
		
		add_submenu_page('bhaa' ,'BHAA','Help','manage_options', 'help' , array(&$this, 'help'));

		add_submenu_page('bhaa' ,'BHAA','Import','manage_options', 'import' , array(&$this->import, 'dispatch'));
		
		add_options_page( 'BHAA Plugin Options', 'BHAA Plugin', 'manage_options', 'my-unique-identifier', 'bhaa_plugin_options');
		
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
	
	/**
	 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
	 * http://wordpress.org/support/topic/converting-geeklog-to-wordpress?replies=7
	 */
	public function import()
	{
		$this->header();
		$this->greet();
		$this->footer();
	}
	
	function header()
	{
		echo '<div class="wrap">';
		echo '<h2>'.__('Import BHAA').'</h2>';
		echo '<p>'.__('Steps may take a few minutes depending on the size of your database. Please be patient.').'</p>';
	}
	
	function footer()
	{
		echo '</div>';
	}
		
	function greet()
	{
		echo '<p>'.__('This importer allows you to import BHAA stuff.').'</p>';
		echo '<p>'.__('Hit the links below and pray:').'</p>';
		echo '<a href="admin.php?import=bhaa&action=events">Import BHAA Events</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=users">Import BHAA Users</a><br/>';
		//		echo '<form action="admin.php?import=geeklog&amp;step=1" method="post">';
		//	$this->db_form();
		//echo '<input type="submit" name="submit" value="'.__('Import Categories').'" />';
		//echo '</form>';
	}
}