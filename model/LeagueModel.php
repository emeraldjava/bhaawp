<?php
class LeagueModel extends BaseModel
{
	var $leagueid;
	
	function __construct($leagueid)
	{
		parent::__construct();
		$this->leagueid = $leagueid;
	}
	
	public function getLeagueTagsAndRaces($league,$gender)
	{
		$select = $this->select()
		->setIntegrityCheck(false)
		->from(array('leagueevent'=>'leagueevent'),
				array('(CASE leagueevent.summaryrace WHEN 0 THEN race.id ELSE leagueevent.summaryrace END) as raceid'))
				->join(array('event'=>'event'),'event.id = leagueevent.event',array('tag'))
				->join(array('race'=>'race'),'event.id = race.event',array())
				->where('leagueevent.league = ?',$league)
				->where("race.type in ('C','S',?)",$gender)
				->group('raceid')
				->order('event.date ASC');
		return $this->fetchAll($select);
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