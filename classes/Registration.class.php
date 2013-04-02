<?php
/**
 * handles the raceday registration application
 * 
 * User Search
 * - http://www.blackbam.at/blackbams-blog/2011/06/27/wordpress-improved-user-search-first-name-last-name-email-in-backend/
 * - http://plugins.svn.wordpress.org/improved-user-search-in-backend/tags/1.2.3/improved-user-search-in-backend.php
 */
class Registration
{
	function __construct()
	{
		
		// ajax action
		//add_action('wp_ajax_nopriv_bhaa_reg_add_runner', array($this,'bhaa_reg_add_runner'));
		add_action('wp_ajax_bhaa_reg_add_runner', array($this,'bhaa_reg_add_runner'));
		
		add_action( 'admin_action_bhaa_register_runner', array($this,'admin_action_bhaa_register_runner') );
	}
	
	function registration()
	{
		if(is_user_logged_in()||current_user_can('manage_options'))
		{
			//echo var_dump($_REQUEST);
			return '
				[tabs tabmember="Details" tabnewday="NewMember" tablist="List" tabexport="Export"]
					[tab id=member]Register Member'.$this->bhaa_register_runner_ajax_form().'[/tab]
					[tab id=newday]Day/New Member[/tab]
					[tab id=list]List Runners[/tab]
					[tab id=export]Export Racetec file[/tab]
				[/tabs]';
			//echo $this->form_ajax();
		}
		else 
		{
			return '<h2>You must be logged in with specific permissions to access this page</h2>';
		}
	}
	
	// http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
// 	function form_101()
// 	{
// 		echo '<form method="POST" action="'.admin_url( 'admin.php' ).'">'.
// 			'<input type="hidden" name="action" value="101" />'.
// 			'<input type="submit" value="Do it!" />'.
// 			'</form>';
// 	}
	
	// http://www.andrewmpeters.com/blog/how-to-make-jquery-ajax-json-requests-in-wordpress/
	// http://pippinsplugins.com/post-data-with-ajax-in-wordpress-pugins/
	// http://stackoverflow.com/questions/1960240/jquery-ajax-submit-form
	function bhaa_register_runner_ajax_form()
	{
		// url: bhaaAjax.ajaxurl
		return '<h2>Register Runner</h2><br/>
			<form id="bhaa_reg_add_runner" method="POST"><br/>
			Runner ID : <input type="text" name="runner"/><br/>
			Race Number : <input type="text" name="racenumber"/><br/>
			<input type="hidden" name="action" value="bhaa_register_runner" /><br/>
			<input type="submit" name="bhaa_reg_add_runner" value="Register" /><br/>
			</form><br/>
			<div id="bhaa_reg_add_runner_response"/>';
	}
	
	function admin_action_bhaa_register_runner()
	{
		error_log('admin_action_bhaa_register_runner');
		echo 'admin_action_bhaa_register_runner';
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	
	// return the new ID
	function bhaa_reg_add_runner()
	{
		error_log('bhaa_reg_add_runner'); 
		switch($_REQUEST['fn']){
			case 'get_latest_posts':
				$output = $_REQUEST['count'];//ajax_get_latest_posts($_REQUEST['count']);
				break;
			default:
				$output = 'No function specified, check your jQuery.ajax() call';
				break;
		}
		
		// json the output		
		$output=json_encode($output);
		if(is_array($output))
		{
			print_r($output);
		}
		else
		{
			echo $output;
		}
		die;
	}
	
	// jquery form validate
	// http://www.problogdesign.com/wordpress/validate-forms-in-wordpress-with-jquery/
	// http://net.tutsplus.com/tutorials/javascript-ajax/submit-a-form-without-page-refresh-using-jquery/
	// http://wordpress.stackexchange.com/questions/33896/front-end-submit-form-with-jquery-form-plugin
	
	function bhaa_list_entry()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA List Entry</p>';
		echo '</div>';
	}
	
	// http://www.catswhocode.com/blog/how-to-create-a-built-in-contact-form-for-your-wordpress-theme
	function bhaa_export_entry()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Export Entry</p>';
		echo '<form id="bhaa_register_export" method="POST" action="">
		<input type="submit" name="bhaa_reg_add_runner" value="Register" /><br/>
		</form>';
		echo '</div>';
	}
	
	function getEvent()
	{
		$event = new EventModel();
		return $event->getNextEvent();
	}
	
	function getNextRaces()
	{
		$event = new EventModel();
		return $event->getNextRaces();
	}
	
	// 2600 esb
	function registerRunner($race,$runner,$racenumber)
	{
		$raceResult = new RaceResult($race);
		$raceResult->registerRunner($runner,$racenumber);
	}
	
	function listRegisteredRunners($event)
	{
		$event = new EventModel($event);
		return $event->listRegisteredRunners();
	}
	
	/**
	 * Export the csv file for racetec
	 */
	function export()
	{
		
	}
}
?>