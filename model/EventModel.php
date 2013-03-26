<?php

class EventModel extends BaseModel
{
	var $eventid;
	
	function __construct($eventid=0)
	{
		parent::__construct();
		$this->eventid=$eventid;	
	}
	
	function getNextEvent()
	{
		return $this->wpdb->get_results(
			$this->wpdb->prepare('select event_id,post_id,event_slug from wp_em_events 
            	where event_start_date >= NOW()
				order by event_start_date ASC limit 1'));
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