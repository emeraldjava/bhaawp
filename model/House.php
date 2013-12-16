<?php
/**
 * Logic specific to a house, which can mean a active company or sector team
 * @author oconnellp
 */
class House extends BaseModel
{
	const HOUSE = 'house';
	const SECTOR = 'sector';
	
	const TEAM_TYPE = 'teamtype';
	const COMPANY_TEAM = 'company';
	const SECTOR_TEAM = 'sector';
	
	const TEAM_STATUS = 'teamstatus';
	const ACTIVE = 'ACTIVE';
	const PENDING = 'PENDING';
	
	var $houseid;
	
	function __construct($houseid)
	{
		parent::__construct();
		$this->houseid = $houseid;
	}
	
	// true if company
	function isCompany()
	{
		
	}
	
	// indicated an active team
	function isActive()
	{
		
	}
	
	// getTeamResults()
	
	// getTeamMembers()

	// addRunner - removeRunner
}
?>