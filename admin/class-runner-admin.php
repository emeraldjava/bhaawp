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

		add_filter('user_row_actions',array($this,'bhaa_user_row_actions_runner_link'),10,2);
		//add_filter('user_row_actions',array($this,'bhaa_user_row_actions_renew_link'),11,2);

		add_action('user_register',array($this,'bhaa_user_register'),12);
	}
	
	function bhaa_user_register( $user_id ) {
		error_log('bhaa_user_register '.$user_id);
		//if ( isset( $_POST['first_name'] ) )
		//update_user_meta($user_id,'first_name',$_POST['first_name']);
		update_user_meta($user_id,'bhaa_runner_status','D');
		update_user_meta($user_id,'bhaa_runner_gender','M');
		update_user_meta($user_id,'bhaa_runner_dateofbirth','01/01/1980');
	}
	
	/**
	 * handle the custom admin columns
	 */
	function bhaa_manage_users_columns( $column ) {
		unset($column['posts']);
		unset($column['role']);
		$column[Runner::BHAA_RUNNER_STATUS] = __('Status', Runner::BHAA_RUNNER_STATUS);
		$column[Runner::BHAA_RUNNER_DATEOFRENEWAL] = __('Renewal', Runner::BHAA_RUNNER_DATEOFRENEWAL);
		$column[Runner::BHAA_RUNNER_DATEOFBIRTH] = __('DoB', Runner::BHAA_RUNNER_DATEOFBIRTH);
		$column[Runner::BHAA_RUNNER_COMPANY] = __('Company', Runner::BHAA_RUNNER_COMPANY);
		$column[Connections::HOUSE_TO_RUNNER] = __('Team', Connections::HOUSE_TO_RUNNER);
		$column[Connections::SECTORTEAM_TO_RUNNER] = __('SectorTeam', Connections::SECTORTEAM_TO_RUNNER);
		return $column;
	}

	function bhaa_manage_users_custom_column( $val, $column_name, $user_id ) {
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case Runner::BHAA_RUNNER_STATUS:
				return get_user_meta($user_id,Runner::BHAA_RUNNER_STATUS,true);
				break;
			case Runner::BHAA_RUNNER_DATEOFRENEWAL:
				return get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFRENEWAL,true);
				break;
			case Runner::BHAA_RUNNER_DATEOFBIRTH:
				return get_user_meta($user_id,Runner::BHAA_RUNNER_DATEOFBIRTH,true);
				break;
			case Runner::BHAA_RUNNER_COMPANY:
				$company = get_user_meta($user_id,Runner::BHAA_RUNNER_COMPANY,true);
				if(isset($company))
					return sprintf('<a target="_new" href="%s">%s</a>',get_edit_post_link($company),get_the_title($company));
				else
					return get_the_title($company);
				//return post_permalink(get_user_meta($user_id,Runner::BHAA_RUNNER_COMPANY,true));
				//return get_user_meta($user_id,Runner::BHAA_RUNNER_COMPANY,true);
				break;
			case Connections::HOUSE_TO_RUNNER:
				$team = p2p_get_connections(Connections::HOUSE_TO_RUNNER,array('to'=>$user_id));
				//var_dump( $teams );
				if(sizeof($team)==1)
					return sprintf('<a target="_new" href="%s">%s</a>',get_edit_post_link($team[0]->p2p_from),get_the_title($team[0]->p2p_from));
				else
					return 'N/A';
			case Connections::SECTORTEAM_TO_RUNNER :
				$sectorTeam = p2p_get_connections(Connections::SECTORTEAM_TO_RUNNER,array('to'=>$user_id));
				//var_dump( get_edit_post_link($sectorTeam[0]->p2p_from) );
				if(sizeof($sectorTeam)==1)
					return sprintf('<a target="_new" href="%s">%s</a>',get_edit_post_link($sectorTeam[0]->p2p_from),get_the_title($sectorTeam[0]->p2p_from));
				else 
					return 'N/A';
				break;
			default:
		}
		return '';
	}

	/**
	 * Add a renew link
	 * @param unknown $actions
	 * @param unknown $user
	 * @return string
	 */
	function bhaa_user_row_actions_renew_link( $actions, $user ) {
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

	function bhaa_user_row_actions_runner_link( $actions, $user ) {
		if ( current_user_can('manage_options') ) {
			$actions['bhaa_runner_view'] = '<a target="_new" href="/runner/?id='.$user->ID.'">Runner</a>';
		}
		return $actions;
	}

	function getAutoIncrementValue() {
		global $wpdb;
		return $wpdb->get_var('SELECT `AUTO_INCREMENT`
			FROM INFORMATION_SCHEMA.TABLES
			WHERE TABLE_SCHEMA = "bhaaie_wp"
			AND TABLE_NAME = "wp_users"');
	}

	function getMaxRunnerValue() {
		global $wpdb;
		return $wpdb->get_var('SELECT MAX(ID) FROM wp_users');
	}

	function getRunnersWithIdOver30000() {
		global $wpdb;
		return $wpdb->get_results('SELECT ID FROM wp_users WHERE ID>30000 ORDER BY ID DESC LIMIT 20');
	}

	/**
	 * Return the next auto_increment value
	 */
	function getNextRunnerIdAutoInc() {
		global $wpdb;
		$sqlstat = "SHOW TABLE STATUS WHERE name='wp_users'";
		return str_pad($wpdb->get_row($sqlstat)->Auto_increment , 5, 0, STR_PAD_LEFT);
	}

	function getNextRunnerId() {
		global $wpdb;
		return $wpdb->get_var('select l.id + 1
			from wp_users as l
			  left outer join wp_users as r on l.id + 1 = r.id
			where r.id is null
			and l.id>10000 and l.id<50000
			limit 1');
	}
}
?>