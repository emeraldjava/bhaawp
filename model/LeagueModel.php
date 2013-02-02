<?php
class LeagueModel extends BaseModel
{
	var $leagueid;
	
	function __construct($leagueid)
	{
		parent::__construct();
		$this->leagueid = $leagueid;
	}
	
	function getEvents()
	{
		echo $this->leagueid;
	}
}
?>