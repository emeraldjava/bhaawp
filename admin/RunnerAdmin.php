<?php
/**
 * Handles the admin view a specific user/runner
 * @author oconnellp
 *
 */
class RunnerAdmin
{
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

			update_user_meta($user_id, Runner::BHAA_RUNNER_STATUS, 'M');
			update_user_meta($user_id, Runner::BHAA_RUNNER_DATEOFRENEWAL,date('Y-m-d'));
			error_log('bhaa_runner_renew : '.$user_id.' '.$action.','.$user->user_email.','.date('Y-m-d'));

			ob_start();
			require 'renewal.email.php';
			$content = ob_get_clean();
			
			//$content = file_get_contents('./renewal.email.php');
			error_log($content);
				
			
			if(true)//$user->user_email!=''||$user->user_email!=null)
			{
				
				//locate_template('emails/bhaatickets.php', true, array('EM_Booking'=>$EM_Booking));
				//$replace = ob_get_clean();
				
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$message  = sprintf(__('Dear %s:'), $user->user_firstname) . "\n";
				$message  .= sprintf(__('Your BHAA Membership has been renewed by %s:'), $blogname) . "\n";
				$message  .= sprintf(__('Thank you and we look forward to seeing you at the upcoming events.'),'') . "\n";
				//$message  .= sprintf(__('You have to option to leave comments and see / manage your own contact details when you login into the website')) . "\n";
				//$message .= sprintf(__('Username: %s'), stripslashes($user->user_login)) . "\r\n\r\n";
				//$message .= sprintf(__('E-mail: %s'), stripslashes($user->user_email)) . "\r\n";
				//Prepare headers for HTML
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers .= 'From: Business Houses Athletic Association <info@bhaa.ie>' . "\r\n";
				
				// $user->user_email
				$res = wp_mail('paul.oconnell@aegon.ie', 'BHAA Renewal : '.$user->user_firstname." ".$user->user_lastname , $message, $headers);
				//error_log('email sent ? '.$res);
			}
			wp_redirect(wp_get_referer());
			exit();
		}
	}
}
?>