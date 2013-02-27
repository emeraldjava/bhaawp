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
		foreach($races as $raceid) {
			$race = new Race($raceid);
			$res[] = $race;
		}
		//echo 'x'.$res;
		return $res;
	}
}
?>