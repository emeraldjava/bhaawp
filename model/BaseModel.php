<?php
class BaseModel
{
	protected $wpdb;
	
	function __construct()
	{
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	
	function getRegistrationRunnerDetails()
	{
		return $this->wpdb->get_results(
			$this->wpdb->prepare(
				'select wp_users.id as id,wp_users.display_name as label, status.meta_value as status from wp_users
				inner join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key="bhaa_runner_status")
				where status.meta_value in ("M","I") order by id;')
		);
	}
}
?>