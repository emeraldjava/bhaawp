<?php

class EventModel extends BaseModel
{
	var $eventid;
	
	function __construct($eventid=0) {
		parent::__construct();
		$this->eventid=$eventid;	
	}
	
	public function getName() {
		return 'wp_em_events';
	}
	
	function getNextEvent()	{
		return $this->wpdb->get_row('select event_id,post_id,event_slug,
				(select MAX(p2p_to) from wp_p2p where p2p_from=post_id) as race 
				from wp_em_events 
            	where event_start_date >= DATE(NOW())
				order by event_start_date ASC limit 1');
	}
	
	function getNextRaces() {
		return $this->wpdb->get_results("select e.event_id,e.post_id,e.event_slug,r.id,
				r_dist.meta_value as dist,r_type.meta_value as type,r_unit.meta_value as unit 
				from wp_em_events e
				join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e.post_id=e2r.p2p_from)
				join wp_posts r on (r.id=e2r.p2p_to)
				inner join wp_postmeta r_dist on (r_dist.post_id=r.id and r_dist.meta_key='bhaa_race_distance')
				inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
				inner join wp_postmeta r_unit on (r_unit.post_id=r.id and r_unit.meta_key='bhaa_race_unit')
				where event_start_date >= DATE(NOW())
				order by event_start_date ASC, dist DESC limit 1");
	}
	
	/**
	 * Return the details of all registered runner for the website and racetec export
	 */
	function listRegisteredRunners($limit=0,$class='RACE_REG',$order='wp_bhaa_raceresult.racetime desc, wp_bhaa_raceresult.id desc') {
		// order by id,
		$SQL = $this->wpdb->prepare("SELECT wp_bhaa_raceresult.id,runner,racenumber,race,
firstname.meta_value as firstname,lastname.meta_value as lastname,
gender.meta_value as gender,dateofbirth.meta_value as dateofbirth,
standard.meta_value as standard,status.meta_value as status,
house.id as companyid, 
house.post_title as companyname,
CASE WHEN r2s.p2p_from IS NOT NULL THEN r2s.p2p_from ELSE r2c.p2p_from END as teamid,
CASE WHEN r2s.p2p_from IS NOT NULL THEN sectorteam.post_title ELSE house.post_title END as teamname,
standardscoringset
from wp_bhaa_raceresult
JOIN wp_p2p e2r ON (wp_bhaa_raceresult.race=e2r.p2p_to AND e2r.p2p_type='event_to_race')
left JOIN wp_users on (wp_users.id=wp_bhaa_raceresult.runner) 
left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = 'house_to_runner')
left join wp_p2p r2s ON (r2s.p2p_to=wp_users.id AND r2s.p2p_type = 'sectorteam_to_runner')
left join wp_posts house on (house.id=r2c.p2p_from and house.post_type='house')
left join wp_posts sectorteam on (sectorteam.id=r2s.p2p_from and sectorteam.post_type='house')
left join wp_usermeta firstname ON (firstname.user_id=wp_users.id AND firstname.meta_key = 'first_name')
left join wp_usermeta lastname ON (lastname.user_id=wp_users.id AND lastname.meta_key = 'last_name')
left join wp_usermeta gender ON (gender.user_id=wp_users.id AND gender.meta_key = 'bhaa_runner_gender')
left join wp_usermeta dateofbirth ON (dateofbirth.user_id=wp_users.id AND dateofbirth.meta_key = 'bhaa_runner_dateofbirth')
left join wp_usermeta status ON (status.user_id=wp_users.id AND status.meta_key = 'bhaa_runner_status')
left join wp_usermeta standard ON (standard.user_id=wp_users.id AND standard.meta_key = 'bhaa_runner_standard')
where wp_bhaa_raceresult.class=%s
order by ".$order,$class);// AND e2r.p2p_from=%d,$this->eventid);
		
		if($limit!=0)
			$SQL .= " limit ".$limit;
		//error_log($SQL);
		$this->wpdb->query("SET SQL_BIG_SELECTS=1");
		return $this->wpdb->get_results($SQL);
	}
	
	function getRaces() {
		$races = p2p_get_connections(Connections::EVENT_TO_RACE,
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