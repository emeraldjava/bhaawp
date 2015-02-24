<?php
/**
 * Handles the various p2p connections the BHAA use
 * @author oconnellp
 *
 */
class Connections {
	
	const EVENT_TO_RACE = 'event_to_race';
	const LEAGUE_TO_EVENT = 'league_to_event';
	const HOUSE_TO_RUNNER = 'house_to_runner';
	const SECTORTEAM_TO_RUNNER = 'sectorteam_to_runner';
	const TEAM_CONTACT = 'team_contact';
	// indicates a runner who will get 10 league points for organsing a race
	const RACE_ORGANISER = 'race_organiser';
	// indicates a team that 6 leagues points for organising an event
	const TEAM_POINTS = 'team_points';
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		add_action('p2p_init',array(&$this,'bhaa_connection_types'));
		add_action('p2p_created_connection',array($this,'bhaa_p2p_created_connection'));
		add_action('p2p_delete_connections',array($this,'bhaa_p2p_delete_connections'));
	}
			
	function bhaa_connection_types() {
				
		p2p_register_connection_type( array(
			'name' => Connections::EVENT_TO_RACE,
			'from' => 'event',
			'to' => 'race',
			'cardinality' => 'one-to-many'
		));
		p2p_register_connection_type( array(
			'name' => Connections::LEAGUE_TO_EVENT,
			'from' => 'league',
			'to' => 'event',
			'cardinality' => 'many-to-many'
		));
		p2p_register_connection_type( array(
			'name' => Connections::HOUSE_TO_RUNNER,
			'from' => 'house',
			'to' => 'user',
			'cardinality' => 'one-to-many',
			'title' => array( 'from' => 'Company Runner', 'to' => 'Company' )
		));
		p2p_register_connection_type( array(
			'name' => Connections::SECTORTEAM_TO_RUNNER,
			'from' => 'house',
			'to' => 'user',
			'cardinality' => 'one-to-many',
			'title' => array( 'from' => 'Sector Team Runner', 'to' => 'Sector Team' )
		));
		p2p_register_connection_type( array(
			'name' => Connections::TEAM_CONTACT,
			'from' => 'house',
			'to' => 'user',
			'cardinality' => 'one-to-one',
			'title' => array( 'from' => 'Team Contact', 'to' => 'Team Contact' )
		));
		p2p_register_connection_type( array(
			'name' => Connections::RACE_ORGANISER,
			'from' => 'race',
			'to' => 'user',
			'cardinality' => 'many-to-many',
			'title' => array( 'from' => 'Race Organiser', 'to' => 'Race Organiser')
		));
		p2p_register_connection_type( array(
			'name' => Connections::TEAM_POINTS,
			'from' => 'race',
			'to' => 'house',
			'cardinality' => 'many-to-many',
			'title' => array( 'from' => 'Team Points', 'to' => 'Team Points')
		));
	}
	
	/**
	 * https://github.com/scribu/wp-posts-to-posts/issues/236
	 * @param unknown_type $p2p_id
	 */
	function bhaa_p2p_created_connection($p2p_id) {
		$connection = p2p_get_connection( $p2p_id );
		if( $connection->p2p_type == Connections::HOUSE_TO_RUNNER ) {
			update_user_meta( $connection->p2p_to, Runner::BHAA_RUNNER_COMPANY, $connection->p2p_from);
			//error_log("added HOUSE_TO_RUNNER ".$p2p_id);
		} elseif($connection->p2p_type == Connections::RACE_ORGANISER) {
			$raceResult = new RaceResult($connection->p2p_from);
			$raceResult->addRaceOrganiser($connection->p2p_to);
		} elseif($connection->p2p_type == Connections::TEAM_POINTS) {
			$teamResult = new TeamResult($connection->p2p_from);
			$res = $teamResult->addTeamOrganiserPoints($connection->p2p_to);
		}
		//error_log('bhaa_p2p_created_connection() '.$connection->p2p_type.' '.$connection->p2p_from.' -> '.$connection->p2p_to.'. '.$res);
	}
	
	function bhaa_p2p_delete_connections($p2p_id) {
		$connection = p2p_get_connection( $p2p_id );
		if( $connection->p2p_type == Connections::HOUSE_TO_RUNNER ) {
			delete_user_meta( $connection->p2p_to, Runner::BHAA_RUNNER_COMPANY, $connection->p2p_from);
		} elseif($connection->p2p_type == Connections::RACE_ORGANISER) {
			$raceResult = new RaceResult($connection->p2p_from);
			$raceResult->deleteRaceOrganiser($connection->p2p_to);
		} elseif($connection->p2p_type == Connections::TEAM_POINTS) {
			$teamResult = new TeamResult($connection->p2p_from);
			$res = $teamResult->deleteTeamOrganiserPoints($connection->p2p_to);
		}
		//error_log('bhaa_p2p_delete_connections() '.$connection->p2p_type.' '.$connection->p2p_from.' -> '.$connection->p2p_to.'. '.$res);
	}
	
	/**
	 * Links a runner to a specific house
	 * @param unknown $p2p_type
	 * @param unknown $from
	 * @param unknown $to
	 * @return Ambigous <boolean, number, mixed>
	 */
	function updateRunnersHouse($p2p_type,$newCompany,$runner) {
		
		$runnerObj = new Runner($runner);
		$oldCompany = $runnerObj->getCompanyId();

		error_log('updateRunnersHouse('.$runner.') '.$p2p_type.' '.$oldCompany.'-->'.$newCompany);
		
		if(p2p_connection_exists($p2p_type,array('from' => $oldCompany, 'to' => $runner))){
			$d = p2p_type($p2p_type)->disconnect( $oldCompany, $runner);
			error_log('updateRunnersHouse delete old link '.$p2p_type.' '.$oldCompany.' '.$runner.' '.$d);
		}
		
		if(p2p_connection_exists($p2p_type,array('from' => $newCompany, 'to' => $runner))) {
			error_log('updateRunnersHouse company connection exists '.$p2p_type.' '.$newCompany.' '.$runner.' '.$re);
		} else {
			
			$re = p2p_type($p2p_type)->connect( $newCompany, $runner );
			if ( is_wp_error($re) )
				error_log('updateRunnersHouse create '.$p2p_type.' '.$newCompany.' '.$runner.' '.$re->get_error_message());
			else 
				error_log('updateRunnersHouse create '.$p2p_type.' '.$newCompany.' '.$runner.' '.$re);
		}
		$re2 = update_user_meta( $runner, Runner::BHAA_RUNNER_COMPANY, $newCompany, $oldCompany);
		if ( is_wp_error($re2) )
			error_log($r2e->get_error_message());
		return $re2;
	}
	
	function p2pDetails($user_id) {
		
		$connected = get_posts( array(
			'connected_type' => Connections::HOUSE_TO_RUNNER,
			'connected_items' => $user_id,
			'suppress_filters' => false,
			'nopaging' => true
		) );
		
		//$connected = p2p_type(Connections::HOUSE_TO_RUNNER);
		var_dump($connected);
		//$connected->get_connected( $user_id );
		
		foreach ( $connected as $post )  {
			$msg = '<li>'.$post->post_title;
			
			// Display count and type
			$msg .=  '<br>';
			$msg .=  'Count: ' . p2p_get_meta( $post->p2p_id, 'count', true );
			$msg .=  '<br>';
			$msg .=  'Type: ' . p2p_get_meta( $post->p2p_id, 'type', true );
			
			$msg .=  '</li>';
		}
		return $msg;
	}
	
	function getRunnerConnections($user_id) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare('select * from wp_p2p where p2p_to=%d
			UNION select * from wp_p2p where p2p_from=%d',$user_id,$user_id));
	}
}