<?php
class BaseModel
{
	protected $wpdb;
	
	function __construct()
	{
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	
	function getRegistrationRunnerDetails($status)
	{
		$SQL = $this->wpdb->prepare(
			'select wp_users.id as id,wp_users.id as value,
			wp_users.display_name as label,
			first_name.meta_value as firstname,
			last_name.meta_value as lastname,
			status.meta_value as status,
			gender.meta_value as gender,
			dob.meta_value as dob,
			company.meta_value as company,
			house.post_title as companyname,
			standard.meta_value as standard
			from wp_users
			left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key="first_name")
			left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key="last_name")
			left join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key="bhaa_runner_status")
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta dob on (dob.user_id=wp_users.id and dob.meta_key="bhaa_runner_dateofbirth")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts house on (house.id=company.meta_value and house.post_type="house")
			left join wp_usermeta standard on (standard.user_id=wp_users.id and standard.meta_key="bhaa_runner_standard")
			where status.meta_value IN("'.implode('","',$status).'") order by id;');
		//error_log($SQL);
		return $this->wpdb->get_results($SQL);
	}
}
?>