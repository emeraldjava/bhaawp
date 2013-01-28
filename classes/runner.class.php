<?php
/**
 * The runner class will handle user registration and editing of BHAA specific meta-data fields.
 * 
 * users will use email to login
 * username will be firstname.surname
 * 
 * @author oconnellp
 * 
 * -- add bhaa fields to registration
 * http://wordpress.org/support/topic/howto-custom-registration-fields
 * http://www.tutorialstag.com/create-custom-wordpress-registration-page.html
 *
 * -- use email as the login (we'll set the username based on first last name
 * http://wordpress.stackexchange.com/questions/51678/how-to-login-with-email-only-no-username
 * 
 * http://trepmal.com/action_hook/register_form/
 * http://www.rarescosma.com/2011/04/wordpress-hooks-user_register/
 * 
 * -- show bhaa logo on the login/registration page
 * http://www.paulund.co.uk/change-wordpress-login-logo-without-a-plugin
 * 
 * -- admin user columns
 * http://w4dev.com/wp/add-last-login-time-in-wp-admin-users-column/
 * 
 * http://stackoverflow.com/questions/9792418/how-do-i-select-user-id-first-and-last-name-directly-from-wordpress-database
 
 http://mattvarone.com/wordpress/list-users-with-wp_user_query/ 
 http://codex.wordpress.org/Class_Reference/WP_User_Query
 http://wp.smashingmagazine.com/2012/06/05/front-end-author-listing-user-search-wordpress/
 
 CIMY
 http://wordpress.org/extend/plugins/cimy-user-extra-fields/developers/
 http://blog.ftwr.co.uk/archives/2009/07/19/adding-extra-user-meta-fields/
 
 	// USER SEARCH
	
	// widget - http://plugins.svn.wordpress.org/advanced-search-widget/tags/0.2/advanced-search-widget.php
	
	// http://wp.smashingmagazine.com/2012/06/05/front-end-author-listing-user-search-wordpress/
//	http://maorchasen.com/blog/2012/09/19/using-wp_user_query-to-get-a-user-by-display_name/
//  http://www.tomauger.com/2012/tips-and-tricks/expanded-user-search-in-wordpress	


 */
class Runner
{	
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_FIRSTNAME = 'bhaa_runner_firstname';
	const BHAA_RUNNER_LASTNAME = 'bhaa_runner_lastname';
	
	const BHAA_RUNNER_DATEOFBIRTH = 'bhaa_runner_dateofbirth';
	const BHAA_RUNNER_COMPANY = 'bhaa_runner_company';
	const BHAA_RUNNER_NEWSLETTER = 'bhaa_runner_newsletter';
	const BHAA_RUNNER_TEXTALERT = 'bhaa_runner_textalert';
	const BHAA_RUNNER_MOBILEPHONE = 'bhaa_runner_mobilephone';
	const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	const BHAA_RUNNER_TERMS_AND_CONDITIONS = 'bhaa_runner_terms_and_conditions';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	const BHAA_RUNNER_GENDER = 'bhaa_runner_gender';
	const MAN = 'M';
	const WOMAN = 'W';
	
	const BHAA_RUNNER_STANDARD = 'bhaa_runner_standard';
	
	function __construct()
	{
		// display the admin status column
		add_filter('manage_users_columns',array($this,'bhaa_manage_users_columns'));
		add_filter('manage_users_custom_column',array($this,'bhaa_manage_users_custom_column'), 10, 3 );
		
		add_filter('user_row_actions',array( &$this,'bhaa_runner_renew_link'),10,2);
		add_action('init',array(&$this,'bhaa_runner_renew_action'),11);
	}
		
	/**
	 * handle the custom admin columns
	 */
	function bhaa_manage_users_columns( $column ) {
		$column['status'] = __('Status', 'status');
		return $column;
	}
	
	function bhaa_manage_users_custom_column( $val, $column_name, $user_id )
	{
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case 'status' :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_STATUS,true).' '.get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFRENEWAL,true);
				break;
			default:
		}
		return $return;
	}
	
	/**
	 * Add a renew link
	 * @param unknown $actions
	 * @param unknown $user
	 * @return string
	 */
	function bhaa_runner_renew_link( $actions, $user ){
		if ( current_user_can('manage_options') ) {
			//$bookings_link = em_add_get_params($my_bookings_page, array('person_id'=>$user->ID), false);
			$bookings_link = EM_ADMIN_URL. "&action=bhaa_runner_renew&id=".$user->ID;
			$actions['renew'] = "<a href='$bookings_link'>" . __( 'Renew','bhaa' ) . "</a>";
		}
		return $actions;
	}
	
	/**
	 * Renew action
	 */
	function bhaa_runner_renew_action()
	{
		if ( $_REQUEST['action'] == 'bhaa_runner_renew')
		{
			$user_id = $_GET['id'];
			$action = $_GET['action'];
			$user = get_userdata($user_id);
			
// 			if($user->user_email!=''||$user->user_email!=null)
// 			{
// 				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
// 				$message  = sprintf(__('Dear %s:'), $user->user_firstname) . "\n";
// 				$message  = sprintf(__('Your BHAA Membership has been renewed %s:'), $blogname) . "\n";
// 				//$message .= sprintf(__('Username: %s'), stripslashes($user->user_login)) . "\r\n\r\n";
// 				//$message .= sprintf(__('E-mail: %s'), stripslashes($user->user_email)) . "\r\n";
// 				//Prepare headers for HTML
// 				$headers  = 'MIME-Version: 1.0' . "\r\n";
// 				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
// 				$headers .= 'From: Business Houses Athletic Association <info@bhaa.ie>' . "\r\n";
				
// 				$res = wp_mail($user->user_email, 'BHAA Renewal : '.$user->user_firstname." ".$user->user_lastname , $message, $headers);
// 				error_log('email sent ? '.$res);
// 			}

			update_user_meta($user_id, Runner::BHAA_RUNNER_STATUS, 'M');
			update_user_meta($user_id, Runner::BHAA_RUNNER_DATEOFRENEWAL,date('Y-m-d'));
			error_log('bhaa_runner_renew : '.$user_id.' '.$action.','.$user->user_email.','.date('Y-m-d'));
			wp_redirect(wp_get_referer());
			exit();
		}
	}
}
?>