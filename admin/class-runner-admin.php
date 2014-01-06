<?php
/**
 * Handles the admin view a specific user/runner
 * @author oconnellp
 *
 * http://wordpress.stackexchange.com/questions/79898/trigger-custom-action-when-setting-button-pressed
 */
class RunnerAdmin {
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		// display the admin status column
		add_filter('manage_users_columns',array($this,'bhaa_manage_users_columns'));
		add_filter('manage_users_custom_column',array($this,'bhaa_manage_users_custom_column'), 10, 3 );

		add_filter('user_row_actions',array($this,'bhaa_runner_renew_link'),10,2);
		add_action('admin_init',array($this,'bhaa_runner_renew_action'),12);
	}

	/**
	 * handle the custom admin columns
	 */
	function bhaa_manage_users_columns( $column ) {
		unset($column['posts']);
		unset($column['role']);
		$column[Runner::BHAA_RUNNER_STATUS] = __('Status', Runner::BHAA_RUNNER_STATUS);
		$column[Runner::BHAA_RUNNER_DATEOFRENEWAL] = __('RenewalDate', Runner::BHAA_RUNNER_DATEOFRENEWAL);
		$column[Runner::BHAA_RUNNER_DATEOFBIRTH] = __('DoB', Runner::BHAA_RUNNER_DATEOFBIRTH);
		return $column;
	}

	function bhaa_manage_users_custom_column( $val, $column_name, $user_id ) {
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case Runner::BHAA_RUNNER_STATUS :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_STATUS,true);
				break;
			case BHAA_RUNNER_DATEOFRENEWAL :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFRENEWAL,true);
				break;
			case Runner::BHAA_RUNNER_DATEOFBIRTH :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFBIRTH,true);
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
	function bhaa_runner_renew_link( $actions, $user ) {
		if ( current_user_can('manage_options') ) {
			$actions['bhaa_runner_renew'] = '<a href="' . 
				wp_nonce_url(
					add_query_arg('id',$user->ID,
						add_query_arg('action','bhaa_runner_renew')
					),
				'bhaa_runner_renew_'.$user->ID)
			.'">'. __('Renew', 'bhaa').'</a>';
		}
		return $actions;
	}

	/**
	 * TODO this should be moved to the runner class.
	 * Renew action
	 * 
	 * http://pippinsplugins.com/add-custom-links-to-user-row-actions/comment-page-1/#comment-133252
	 */
	function bhaa_runner_renew_action() {
		//error_log('bhaa_runner_renew ID :'.$_GET['id']);
		//error_log('bhaa_runner_renew nonce :'.$_GET['_wpnonce']);
		if ( $_REQUEST['action'] == 'bhaa_runner_renew' 
				&& wp_verify_nonce($_GET['_wpnonce'],'bhaa_runner_renew_'.$_GET['id']) ) {
			error_log('bhaa_runner_renew correct nonce :'.$_GET['id'].' '.$_GET['_wpnonce']);
				
			$runner = new Runner($_GET['id']);
			$runner->renew();

/* 			$user_id = $_GET['id'];
			$action = $_GET['action'];
			$user = get_userdata($user_id);
			
			update_user_meta($user_id, Runner::BHAA_RUNNER_STATUS, 'M');
			update_user_meta($user_id, Runner::BHAA_RUNNER_DATEOFRENEWAL,date('Y-m-d')); */
			//error_log('bhaa_runner_renew : '.$user_id.' '.$user->display_name.','.$user->user_email.','.date('Y-m-d'));
			
			//if($user->user_email!=''||$user->user_email!=null) {
				//ob_start();
				//require 'renewal.php';
				//$content = ob_get_clean();
				//error_log($content);
				
				//$company = '';
				//$company = get_post( get_user_meta($user_id,Runner::BHAA_RUNNER_COMPANY,true) )->post_title;
				
// 				error_log(sprintf('%s %d %s %s %s',
// 					$user->display_name,
// 					$user_id,
// 					get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFBIRTH,true),
// 					get_user_meta($user_id,Runner::BHAA_RUNNER_GENDER,true),
// 					$company,TRUE));
/* 				$message = "<html>renewal email</html>";
				$messages = sprintf($content,
					$user->display_name,
					$user_id,
					$user_id,
					$user->user_email,
					get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFBIRTH,true),
					get_user_meta($user_id,Runner::BHAA_RUNNER_GENDER,true),
					$company);
				error_log($user->user_email.' '.$user->user_firstname." ".$user->user_lastname.' '.$message); */
				
				//Prepare headers for HTML
/* 				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers .= 'From: Business Houses Athletic Association <paul.oconnell@aegon.ie>' . "\r\n"; */
				// info@bhaa.ie
				
				//$res = wp_mail(
					//$user->user_email,
					//'BHAA Renewal 2013 : '.$user->user_firstname.' '.$user->user_lastname,
					//$message); 
				//	$headers); 
					//null);
				//error_log('email sent ? x'.$res.'x');
			//}
			wp_redirect(wp_get_referer());
			exit();
		}
		//wp_redirect(wp_get_referer());
		//exit();
	}
}
?>