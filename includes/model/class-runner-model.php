<?php
class RunnerModel extends BaseModel {

	function __construct() {
		parent::__construct();
	}

	public function getName() {
		return 'wp_users';
	}

	function getRegistrationRunnerDetails($status=array('M'),$limit=6000,$output_type='OBJECT',&$resultCount) {
		$this->getWpdb()->query('SET SQL_BIG_SELECTS=1');

		$IN = "'" . implode ( "', '", $status ) . "'";
		//var_dump($IN);

		// http://stackoverflow.com/questions/907806/php-mysql-using-an-array-in-where-clause
		//$SQL = $this->wpdb->prepare(
			$SQL =	'select wp_users.id as id,
			TRIM(wp_users.id) as value,
			TRIM(wp_users.display_name) as label,
			TRIM(first_name.meta_value) as firstname,
			TRIM(last_name.meta_value) as lastname,
			wp_users.user_email as email,
			status.meta_value as status,
			gender.meta_value as gender,
			company.meta_value as company,
			TRIM(house.post_title) as companyname,
			standard.meta_value as standard,
			dob.meta_value as dob
			from wp_users
			left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key="first_name")
			left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key="last_name")
			left join wp_usermeta dob on (dob.user_id=wp_users.id and dob.meta_key="bhaa_runner_dateofbirth")
			left join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key="bhaa_runner_status")
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts house on (house.id=company.meta_value and house.post_type="house")
			left join wp_usermeta standard on (standard.user_id=wp_users.id and standard.meta_key="bhaa_runner_standard")
			where status.meta_value IN('.$IN.') order by status.meta_value,lastname,firstname LIMIT '.$limit;
		//var_dump($SQL);
//		error_log($SQL);
//		error_log('$output_type '.$output_type);
		$res = $this->getWpdb()->get_results($SQL,$output_type);
		$resultCount = $this->getWpdb()->num_rows;
		return $res;
	}

	function expectRaceMasterData($status=array('M','I','D'),$limit=16000,$output_type='OBJECT',&$resultCount) {
		$this->getWpdb()->query('SET SQL_BIG_SELECTS=1');

		$IN = "'" . implode ( "', '", $status ) . "'";
		$SQL =	'select TRIM(wp_users.id) as id,
			TRIM(wp_users.display_name) as label,
			TRIM(first_name.meta_value) as firstname,
			TRIM(last_name.meta_value) as lastname,
			wp_users.user_email as email,
			status.meta_value as status,
			gender.meta_value as gender,
			company.meta_value as company,
			TRIM(house.post_title) as companyname,
			standard.meta_value as standard,
			dob.meta_value as dob
			from wp_users
			left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key="first_name")
			left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key="last_name")
			left join wp_usermeta dob on (dob.user_id=wp_users.id and dob.meta_key="bhaa_runner_dateofbirth")
			left join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key="bhaa_runner_status")
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts house on (house.id=company.meta_value and house.post_type="house")
			left join wp_usermeta standard on (standard.user_id=wp_users.id and standard.meta_key="bhaa_runner_standard")
			where status.meta_value IN('.$IN.') order by status.meta_value,lastname,firstname LIMIT '.$limit;
		//var_dump($SQL);
//		error_log($SQL);
//		error_log('$output_type '.$output_type);
		$res = $this->getWpdb()->get_results($SQL,$output_type);
		$resultCount = $this->getWpdb()->num_rows;
		return $res;
	}



	/**
	 * Match runners by run count and status
	 * - interested in run count 0 and blank status
	 * TODO
	 * select r.id,r.display_name
		from wp_users r
		left join wp_bhaa_raceresult rr on r.id=rr.runner
		join wp_usermeta status ON (status.user_id=r.id AND status.meta_key = 'bhaa_runner_status')
		where status.meta_value='d' and rr.runner is null;
	 * -
	 */
	function getRegistrationRunnerDetailxs($status=array('M'),$limit=1000,&$resultCount) {

	}

	function getRunnersWithStandard($standard,$status='M') {
		return $this->getWpdb()->get_results(
			$this->getWpdb()->prepare('SELECT wp_users.id,wp_users.display_name from wp_users
                join wp_usermeta m_std
                  on (m_std.meta_value=%d and m_std.meta_key="bhaa_runner_standard" and m_std.user_id=wp_users.id)
                join wp_usermeta m_status
                  on (m_status.user_id=wp_users.id and m_status.meta_key="bhaa_runner_status" and m_status.meta_value="M")
                WHERE m_std.meta_value=%d',$standard,$standard)
		);
	}

	/**
	 * A break down of the runners per membership status
	 */
	function getMembershipStatus() {
		return $this->getWpdb()->get_results(
			'SELECT status.meta_value as status,
			COUNT(DISTINCT(status.user_id)) as count from wp_usermeta status
			WHERE status.meta_key="bhaa_runner_status"
			GROUP BY status.meta_value');
	}
}
?>