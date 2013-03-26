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
	
	function getNextRaces()
	{
		return $this->wpdb->get_results(
			$this->wpdb->prepare("select e.event_id,e.post_id,e.event_slug,r.id,
				r_dist.meta_value as dist,r_type.meta_value as type,r_unit.meta_value as unit 
				from wp_em_events e
				join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e.post_id=e2r.p2p_from)
				join wp_posts r on (r.id=e2r.p2p_to)
				inner join wp_postmeta r_dist on (r_dist.post_id=r.id and r_dist.meta_key='bhaa_race_distance')
				inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
				inner join wp_postmeta r_unit on (r_unit.post_id=r.id and r_unit.meta_key='bhaa_race_unit')
				where event_start_date >= NOW()
				order by event_start_date ASC, dist DESC limit 2"));
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