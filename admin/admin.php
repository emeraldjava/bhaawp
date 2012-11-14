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
 		add_action( 'admin_menu', array(&$this, 'bhaa_admin_plugin_menu') );
	}
	
	function bhaa_admin_plugin_menu()
	{
		add_options_page( 'BHAA Plugin Options', 'BHAA', 'manage_options', 'bhaa-options', array(&$this,'bhaa_plugin_options'));
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