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
	
	function getInactiveCompanies()
	{
		return $this->queryHousesByTypeAndStatus(House::COMPANY_TEAM,House::ACTIVE,'NOT IN');
	}	
	
	function getActiveSectors()
	{
		return $this->queryHousesByTypeAndStatus(House::SECTOR_TEAM,House::ACTIVE);
	}
		
	// http://wordpress.stackexchange.com/questions/43585/sorting-by-custom-posts-with-attachments
	function queryHousesByTypeAndStatus($teamType,$teamStatus,$operation='IN')
	{
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
}
?>