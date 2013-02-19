<?php

class EventModel extends BaseModel
{
	var $eventid;
	
	function __construct($eventid)
	{
		$this->eventid=$eventid;	
	}
	
	function getRaces()
	{
		$races = p2p_get_connections(Connection::EVENT_TO_RACE,
			array('direction'=>'from','from'=>$this->eventid,'fields'=>'p2p_to'));
		//echo 'races'.print_r($races);
		
		$res = array();
		foreach($races as $race) {
			$raceM = new RaceModel($race);
			$res[] = $raceM->getKmDistance();
		}
		//echo 'x'.$res;
		return $res;
	}
}
?>