<?php
/**
 * Logic specific to a house, which can mean a active company or sector team
 * @author oconnellp
 */
class House {
	
	const HOUSE = 'house';
	const SECTOR = 'sector';
	
	const TEAM_TYPE = 'teamtype';
	const COMPANY_TEAM = 'company';
	const SECTOR_TEAM = 'sector';
	
	const TEAM_STATUS = 'teamstatus';
	const ACTIVE = 'ACTIVE';
	const PENDING = 'PENDING';
	
	var $houseid;
	var $teamType;
	var $connectionType;
	
	function __construct($houseid) {
		$this->houseid = $houseid;
		$terms = wp_get_post_terms($this->houseid,House::TEAM_TYPE);
		$this->teamType=$terms[0]->name;
		$this->connectionType = Connections::HOUSE_TO_RUNNER;
		if($this->teamType==HOuse::SECTOR_TEAM) {
			$connected_type = Connections::SECTORTEAM_TO_RUNNER;
		}	
	}
	
	function getTeamType(){
		$this->teamType;
	}
	
	function getConnectionType(){
		return $this->connectionType;
	}
		
	function getLinkedRunners() {
		$users = get_users( array(
			'connected_type' => $this->getConnectionType(),
			'connected_items' => $this->houseid,
			'fields' => 'all_with_meta',
			'orderby' => 'display_name',
			'order' => 'ASC'
		));
		//var_dump($users[0],true);
		
		$runners = array();
		foreach ( $users AS $user ) {
			$runners[] = new Runner($user->ID);
		}

		//var_dump($runners[0],true);
	    //var_dump($x,true);
		//return $runners;
		
		$x = array_values($runners);
		return $x;
	}
	
	function getLinkedRunners2(){
		$usersByCompanyQuery = new WP_User_Query(
				array(
						'fields' => 'all_with_meta',
						'orderby' => 'bhaa_runner_dateofrenewal',
						'query_id'=>'get_bhaa_meta',
						'meta_query' => array(
								array(
										'key' => 'bhaa_runner_company',
										'value' => $this->houseid,
										'compare' => '=')
						)
				)
		);
		$users = $usersByCompanyQuery->get_results();
		//var_dump($users);
		//$x = array_values($users);
		//var_dump($x[0]);
		return $x;//users;
	}
	

	
	function house_website_url(){
		$site = get_post_meta(get_the_ID(),'bhaa_company_website',true);
		if(!empty($site))
			return 'Visit the '.get_the_title().' website : <a target="new" href="'.$site.'">'.$site.'</a>';
	}
		
	/**
	 * Use the template 'house-runner' to show the runners linked to a house.
	 * @return string
	 */
	function displayRunnersTable() {
		return Bhaa_Mustache::get_instance()->loadTemplate('house-runners')->render(
			array('users' => $this->getLinkedRunners()
		));
	}
	
	//add_action( 'pre_user_query', 'wps_pre_user_query' );
	/*
	 * Modify the WP_User_Query appropriately
	*
	* Checks for the proper query to modify and changes the default user_login for $wpdb->usermeta.meta_value
	*
	* @param WP_User_Query Object $query User Query object before query is executed
	*/
	// http://stackoverflow.com/questions/12392847/wordpress-user-query-order-by-meta-value
	//function wps_pre_user_query( &$query ) {
	//	global $wpdb;
	//	if ( isset( $query->query_vars['query_id'] ) && 'wps_last_name' == $query->query_vars['query_id'] )
	//		$query->query_orderby = str_replace( 'user_login', "$wpdb->usermeta.meta_value", $query->query_orderby );
	//}
	
	function getRunners(){
		$usersByCompanyArgs = new WP_User_Query(
				array(
						//'exclude' => array($user->ID),
						'fields' => 'all',//_with_meta',
						'orderby' => 'display_name',
						'order' => 'ASC',
						'meta_query' => array(
								//'relation' => 'AND',
								array(
										'key' => 'bhaa_runner_company',
										'value' => $this->houseid,
										'compare' => '=')
								//array(
//										'key' => 'bhaa_runner_dateofrenewal',
	//									'value' => '2014',
		//								'compare' => 'LIKE',
										//'type'=>'DATE')
						)
				)
		);
		//var_dump($usersByCompanyArgs);
		//$userQuery = new WP_User_Query( $usersByCompanyArgs );
		
//		var_dump($usersByCompanyArgs);
		
		return $usersByCompanyArgs->get_results();

		
	}
	
	// http://wordpress.org/support/topic/orderby-custom-field-meta_key
	/*function sort_by_year_status_name( $vars ) {
		if ( isset( $vars->query_vars['orderby'] ) && 'bhaa_sort_by_dor' == $vars->query_vars['orderby'] ) {
			$vars->query_orderby = 'ORDER BY bhaa_runner_dateofrenewal ASC';
		}
		return $vars;
	}*/
	//add_filter( 'pre_user_query', 'sort_by_member_number' );
	
	// getTeamResults()
	
	// getTeamMembers()

	// addRunner - removeRunner
}
?>