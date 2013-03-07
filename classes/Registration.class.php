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
		add_action( 'admin_action_101', array($this,'admin_action_101') );
	}
	
	function registration()
	{
		if(is_user_logged_in()||current_user_can('manage_options'))
		{
			echo "This will be the BHAA race day race number allocation app.";
			echo $this->form_101();
		}
		else 
		{
			echo '<h2>You must be logged in with specific permissions to access this page</h2>';
		}
	}
	
	// http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
	function form_101()
	{
		echo '<form method="POST" action="'.admin_url( 'admin.php' ).'">'.
			'<input type="hidden" name="action" value="101" />'.
			'<input type="submit" value="Do it!" />'.
			'</form>';
	}
	
	function admin_action_101()
	{
		echo 'admin_action_101';	
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
}
?>