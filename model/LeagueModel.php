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
			array('direction'=>'from','from'=>$this->leagueid,'fields'=>'p2p_to')
		);
		//echo 'races'.print_r($events);

		$x = new WP_Query(array(
				'connected_type' => 'league_to_event',
				//'post_type' => 'league',connected_items
				'connected_items' => $this->leagueid,//'any',
				'nopaging' => true,
			)
		);
		echo $x->request;
		echo $x->get_posts();
		
		return print_r($x->get_posts(),true);
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