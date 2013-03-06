<?php
class LeagueModel extends BaseModel
{
	var $leagueid;
	
	function __construct($leagueid)
	{
		parent::__construct();
		$this->leagueid = $leagueid;
	}
	
	function getLeagueRaces($type='')
	{
		$SQL = $this->wpdb->prepare("select l.ID as lid,l.post_title,
			e.ID as eid,e.post_title as etitle,
			r.ID as rid,r.post_title as rtitle,r_type.meta_value as rtype 
			from wp_posts l
			inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
			inner join wp_posts e on (e.id=l2e.p2p_to)
			inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
			inner join wp_posts r on (r.id=e2r.p2p_to)
			inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
			where l.post_type='league'
			and l.ID=%d", $this->leagueid);
		if($type!='')
			$SQL .= sprintf("and r_type.meta_value in ('C','%s')",$type);
		echo $SQL;
		error_log($SQL);
		// OBJECT, OBJECT_K, ARRAY_A, ARRAY_N
		return $this->wpdb->get_results($SQL,OBJECT);
	}
	
	function getRunnerLeagueSummary($races,$runner)
	{
		// $this->wpdb->prepare
		$SQL = sprintf("select e.ID as eid,race,leaguepoints from wp_bhaa_raceresult 
			inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=race)
			inner join wp_posts e on (e.id=e2r.p2p_from)
			where race in ( %s )
			and runner=%d;",$races,$runner);
		//echo $SQL;
		return $this->wpdb->get_results($SQL,OBJECT);
	}
	
	/**
	 * http://zanematthew.com/blog/wordpress-advanced-meta-query-using-wp_query/
	 * https://github.com/scribu/wp-posts-to-posts/issues/322
	 * @return mixed
	 */
	function getEvents()
	{
		$events = p2p_get_connections(Connection::LEAGUE_TO_EVENT,
			array('direction'=>'from','from'=>$this->leagueid,'fields'=>'p2p_to')
		);
		//echo 'races'.print_r($events);

		$x = new WP_Query(array(
				'connected_to'=>'to',
				'connected_type' => 'league_to_event',
				'connected_items' => $this->leagueid,
				'nopaging' => true,
			)
		);
		echo $x->request;
		echo $x->post_count;
		
		return 0;
		//return print_r($x->get_posts(),true);
		// 	$res = array();
		// 	foreach($races as $raceid) {
		// 		$race = new RaceModel($raceid);
		// 		$res[] = $race;
		// 	}
		//echo 'x'.$res;
		//return $events;
	}
}
?>