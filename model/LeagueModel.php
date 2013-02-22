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
		$events = p2p_get_connections(Connection::LEAGUE_TO_EVENT,
			array('direction'=>'from','from'=>$this->leagueid,'fields'=>'p2p_to'));
		echo 'races'.print_r($events);
		// 	$res = array();
		// 	foreach($races as $raceid) {
		// 		$race = new RaceModel($raceid);
		// 		$res[] = $race;
		// 	}
		//echo 'x'.$res;
		return print_r($events);
	}
}
?>