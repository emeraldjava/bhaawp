<?php
class RunnerModel extends BaseModel {

	function __construct() {
		parent::__construct();
	}

	public function getName() {
		return 'wp_users';
	}

	function getRegistrationRunnerDetails($status,$limit=10) {
		$this->wpdb->query('SET SQL_BIG_SELECTS=1');
		// http://stackoverflow.com/questions/907806/php-mysql-using-an-array-in-where-clause
		$SQL = $this->wpdb->prepare(
			'select wp_users.id as id,wp_users.id as value,
			wp_users.display_name as label,
			first_name.meta_value as firstname,
			last_name.meta_value as lastname,
			status.meta_value as status,
			gender.meta_value as gender,
			company.meta_value as company,
			house.post_title as companyname,
			standard.meta_value as standard
			from wp_users
			left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key="first_name")
			left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key="last_name")
			left join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key="bhaa_runner_status")
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts house on (house.id=company.meta_value and house.post_type="house")
			left join wp_usermeta standard on (standard.user_id=wp_users.id and standard.meta_key="bhaa_runner_standard")
			where status.meta_value IN("%s") order by lastname,firstname LIMIT %d',implode(",",$status),$limit);
		//error_log($SQL);
		return $this->wpdb->get_results($SQL);
	}

	function getRunnersWithStandard($standard,$status='M') {
		return $this->wpdb->get_results(
			$this->wpdb->prepare('SELECT wp_users.id,wp_users.display_name from wp_users
                join wp_usermeta m_std
                  on (m_std.meta_value=%d and m_std.meta_key="bhaa_runner_standard" and m_std.user_id=wp_users.id)
                join wp_usermeta m_status
                  on (m_status.user_id=wp_users.id and m_status.meta_key="bhaa_runner_status" and m_status.meta_value="M")
                WHERE m_std.meta_value=%d',$standard,$standard)
		);
	}
}
?>