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
		if ( $_REQUEST['action'] == 'bhaa_runner_renew' 
				&& wp_verify_nonce($_GET['_wpnonce'],'bhaa_runner_renew_'.$_GET['id']) ) {
			$runner = new Runner($_GET['id']);
			$runner->renew();
			wp_redirect(wp_get_referer());
			exit();
		}
	}
}
?>