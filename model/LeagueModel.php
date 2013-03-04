<?php
class LeagueModel extends BaseModel
{
	var $leagueid;
	
	function __construct($leagueid)
	{
		parent::__construct();
		$this->leagueid = $leagueid;
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
				'connected_to'=>'any',
				'connected_type' => 'league_to_event',
				'connected_items' => $this->leagueid,
				'nopaging' => true,
//				'fields'=>'ids'
			)
		);
		echo $x->request;
		var_dump($x);
		
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