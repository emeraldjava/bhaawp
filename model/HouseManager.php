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
	{
		return $this->queryHousesByTypeAndStatus(House::COMPANY_TEAM,House::ACTIVE);
	}
	
	function getActiveSectors()
	{
		return $this->queryHousesByTypeAndStatus(House::SECTOR_TEAM,House::ACTIVE);
	}
	
	function getInactiveCompanies()
	{}
	
	// http://wordpress.stackexchange.com/questions/43585/sorting-by-custom-posts-with-attachments
	function queryHousesByTypeAndStatus($teamType,$teamStatus)
	{
		//$query = new WP_Query();
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
		echo $companyList->request;
		return $companyList->get_posts();
	}
}
?>