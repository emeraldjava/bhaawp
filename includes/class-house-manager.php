<?php
/**
 * The House Manager
 * @author oconnellp
 */
class House_Manager {
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
	}
	
	function getActiveCompanies() {
		return $this->queryHousesByTypeAndStatus(House::COMPANY_TEAM,House::ACTIVE);
	}
	
	function getInactiveCompanies() {
		return $this->queryHousesByTypeAndStatus(House::COMPANY_TEAM,House::ACTIVE,'NOT IN');
	}	
	
	function getActiveSectors() {
		return $this->queryHousesByTypeAndStatus(House::SECTOR_TEAM,House::ACTIVE);
	}
		
	// http://wordpress.stackexchange.com/questions/43585/sorting-by-custom-posts-with-attachments
	function queryHousesByTypeAndStatus($teamType,$teamStatus,$operation='IN') {
		$companyList = new WP_Query(
			array(
			'post_type' => House::HOUSE,
			'order'		=> 'ASC',
			'post_status' => 'publish',
			'orderby' 	=> 'title',
			'nopaging' => true,
			'tax_query'	=> array(
				'relation' => 'AND',
				array(
					'taxonomy'  => House::TEAM_TYPE,
					'field'     => 'slug',
					'terms'     => $teamType,
					'operation' => $operation)
 				),
 				array(
 					'taxonomy'  => House::TEAM_STATUS,
 					'field'     => 'slug',
 					'terms'     => $teamStatus,
					'operation' => $operation)					
			)
		);
		//echo $companyList->request;
		return $companyList->get_posts();
	}
	
	function getCompanyTeamDropdown($user_id) {
		$sectorTeamQuery = new WP_Query(
			array(
				'post_type' => 'house',
				'order'		=> 'ASC',
				'post_status' => 'publish',
				'orderby' 	=> 'title',
				'nopaging' => true,
				'tax_query'	=> array(
				array(
					'taxonomy'  => 'teamtype',
					'field'     => 'slug',
					'terms'     => 'sector', // exclude house posts in the sectorteam custom teamtype taxonomy
					'operator'  => 'IN')
			))
		);
		$sectorTeamIds = implode(',',array_map(function($val){return $val->ID;},$sectorTeamQuery->posts) );
		$args = array (
				'id' => 'companyteam',
				'name' => 'companyteam',
				'echo' => 0,
				'post_type' => 'house',
				'exclude' => $sectorTeamIds
		);
		$selected = get_user_meta($user_id,Runner::BHAA_RUNNER_COMPANY,true);
		// set the correct defaults for new or existing user
		if($selected!=0&&$selected!='') {
			$args = array_merge( $args, array( 'selected' => $selected ) );
		}
		//var_dump($user_id." company ".$selected);
		//var_dump($args);
		return wp_dropdown_pages($args);
	}
	
	function getSectorTeamDropdown() {
		$sectorTeamQuery = new WP_Query(
			array(
				'post_type' => 'house',
				'order'		=> 'ASC',
				'post_status' => 'publish',
				'orderby' 	=> 'title',
				'nopaging' => true,
				'tax_query'	=> array(
					array(
						'taxonomy'  => 'teamtype',
						'field'     => 'slug',
						'terms'     => 'sector', // exclude house posts in the sectorteam custom teamtype taxonomy
						'operator'  => 'IN')
					)
				)
			);
		$sectorTeamIds = implode(',',array_map(function($val){return $val->ID;},$sectorTeamQuery->posts) );
		$args = array (
				'id' => 'sectorteam',
				'name' => 'sectorteam',
				'echo' => 0,
				'post_type' => 'house',
				'include' => $sectorTeamIds
		);
		
		global $current_user;
		//$selected = get_user_meta($current_user->ID,Runner::BHAA_RUNNER_COMPANY,true);
		//var_dump("company ".$selected);
		// set the correct defaults for new or existing user
		if($selected==0||$selected=='') {
			$args = array_merge( $args, array( 'show_option_none' => 'Please select a company' ) );
			$args = array_merge( $args, array( 'option_none_value' => '1' ) );
		} else {
			$args = array_merge( $args, array( 'selected' => $selected ) );
		}
		return wp_dropdown_pages($args);
	}	
}
?>