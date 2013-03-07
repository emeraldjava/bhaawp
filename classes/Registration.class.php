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
		
		// ajax action
		add_action('wp_ajax_bhaa_reg_add_runner',array($this,'bhaa_reg_add_runner'));
	}
	
	function registration()
	{
		if(is_user_logged_in()||current_user_can('manage_options'))
		{
			//echo var_dump($_REQUEST);
			echo "This will be the BHAA race day race number allocation app.";
			echo $this->form_ajax();
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
	
	// http://www.andrewmpeters.com/blog/how-to-make-jquery-ajax-json-requests-in-wordpress/
	// http://pippinsplugins.com/post-data-with-ajax-in-wordpress-pugins/
	// http://stackoverflow.com/questions/1960240/jquery-ajax-submit-form
	function form_ajax()
	{
		// url: bhaaAjax.ajaxurl
		echo '<form id="bhaa_reg_add_runner" method="POST" action="">'.
			'Name: <input type="text" name="name" />'.
			'<input type="hidden" name="action_param" value="101" />'.
			'<input type="submit" name="bhaa_reg_add_runner_submit" value="Do it!" />'.
			'</form>'.
			'<div id="bhaa_reg_add_runner_response"/>';
// 		'<script type="text/javascript">'.
// 	    'var frm = $("#bhaa_reg_add_runner");'.
// 	    'frm.submit(function () {'.
// 	    '    $.ajax({'.
// 	    '        type: frm.attr("method"),'.
// 	    '        url: frm.attr("action"),'.
// 	    '        data: frm.serialize(),'.
// 	    '        success: function (data) {'.
// 	    '            alert("ok");'.
// 	    '        }'.
// 	    '    });'.
// 	    '    return false;'.
// 	    '});'.
// 		'</script>';
	}
	
	function admin_action_101()
	{
		echo 'admin_action_101';	
		
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	
	// return the new ID
	function bhaa_reg_add_runner()
	{
		error_log('bhaa_reg_add_runner'); 
		// add user details
		return 100;
	}
	
	// jquery form validate
	// http://www.problogdesign.com/wordpress/validate-forms-in-wordpress-with-jquery/
	// http://net.tutsplus.com/tutorials/javascript-ajax/submit-a-form-without-page-refresh-using-jquery/
	// http://wordpress.stackexchange.com/questions/33896/front-end-submit-form-with-jquery-form-plugin
}
?>