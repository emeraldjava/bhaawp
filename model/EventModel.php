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
		return p2p_get_connections(Connection::EVENT_TO_RACE,array('from'=>$this->eventid));
	}
}
?>