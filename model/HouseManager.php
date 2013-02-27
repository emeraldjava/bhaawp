<?php
/**
 * 
 * @author oconnellp
 *
 */
class HouseManager extends BaseModel
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getActiveCompanies()
	{}
	
	function getActiveSectors()
	{}
	
	function getInactiveCompanies()
	{}
	
	function queryHousesByTypeAndStatus($teamType,$teamStatus)
	{
		$query = new WP_Query();
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
					'terms'     => $teamType),
				array(
					'taxonomy'  => House::TEAM_STATUS,
					'field'     => 'slug',
					'terms'     => $teamStatus)
				)
			)
		);
		return $query->get_posts();
	}
}
?>