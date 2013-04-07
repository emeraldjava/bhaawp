<?php
class Connection
{
	const EVENT_TO_RACE = 'event_to_race';
	const LEAGUE_TO_EVENT = 'league_to_event';
	const HOUSE_TO_RUNNER = 'house_to_runner';
	const SECTORTEAM_TO_RUNNER = 'sectorteam_to_runner';
	const TEAM_CONTACT = 'team_contact';
	// a runner who get's 10 league points for organsing a race
	const RACE_ORGANISER = 'race_organiser';
	
	function __construct()
	{
		add_action( 'p2p_init', array(&$this,'bhaa_connection_types'));
		add_action( 'p2p_created_connection',array($this,'bhaa_p2p_created_connection'));
		add_action( 'p2p_delete_connections',array($this,'bhaa_p2p_delete_connections'));	
	}
		
	function bhaa_connection_types() {
		// Make sure the Posts 2 Posts plugin is active.
		require_once( ABSPATH . 'wp-content/plugins/posts-to-posts/core/api.php' );
		if ( !function_exists( 'p2p_register_connection_type' ) )
			return;
				
		p2p_register_connection_type( array(
				'name' => Connection::EVENT_TO_RACE,
				'from' => 'event',
				'to' => 'race',
				'cardinality' => 'one-to-many'
		));
		p2p_register_connection_type( array(
				'name' => Connection::LEAGUE_TO_EVENT,
				'from' => 'league',
				'to' => 'event',
				'cardinality' => 'one-to-many'
		));
		p2p_register_connection_type( array(
				'name' => Connection::HOUSE_TO_RUNNER,
				'from' => 'house',
				'to' => 'user',
				'cardinality' => 'one-to-many',
				'title' => array( 'from' => 'Company Runner', 'to' => 'Company' )
		));
		p2p_register_connection_type( array(
				'name' => Connection::SECTORTEAM_TO_RUNNER,
				'from' => 'house',
				'to' => 'user',
				'cardinality' => 'one-to-many',
				'title' => array( 'from' => 'Sector Team Runner', 'to' => 'Sector Team' )
		));
		p2p_register_connection_type( array(
			'name' => Connection::TEAM_CONTACT,
			'from' => 'house',
			'to' => 'user',
			'cardinality' => 'one-to-one',
			'title' => array( 'from' => 'Team Contact', 'to' => 'Team Contact' )
		));
		p2p_register_connection_type( array(
			'name' => Connection::RACE_ORGANISER,
			'from' => 'race',
			'to' => 'user',
			'cardinality' => 'one-to-many',
			'title' => array( 'from' => 'Race Organiser', 'to' => 'Race Organiser')
		));
	}
	
	/**
	 * https://github.com/scribu/wp-posts-to-posts/issues/236
	 * @param unknown_type $p2p_id
	 */
	function bhaa_p2p_created_connection($p2p_id)
	{
		$connection = p2p_get_connection( $p2p_id );
		if( $connection->p2p_type == Connection::HOUSE_TO_RUNNER ) {
			update_user_meta( $connection->p2p_to, Runner::BHAA_RUNNER_COMPANY, $connection->p2p_from);
		}
		elseif($connection->p2p_type == Connection::RACE_ORGANISER) {
			$raceResult = new RaceResult($connection->p2p_from);
			$raceResult->addRaceOrganiser($connection->p2p_to);
		}
		error_log('bhaa_p2p_created_connection() '.$connection->p2p_type.' '.$connection->p2p_from.' -> '.$connection->p2p_to);
	}
	
	function bhaa_p2p_delete_connections($p2p_id)
	{
		$connection = p2p_get_connection( $p2p_id );
		if( $connection->p2p_type == Connection::HOUSE_TO_RUNNER ) {
			delete_user_meta( $connection->p2p_to, Runner::BHAA_RUNNER_COMPANY, $connection->p2p_from);
		}
		elseif($connection->p2p_type == Connection::RACE_ORGANISER) {
			$raceResult = new RaceResult($connection->p2p_from);
			$raceResult->deleteRaceOrganiser($connection->p2p_to);
		}
		error_log('bhaa_p2p_delete_connections() '.$connection->p2p_type.' '.$connection->p2p_from.' -> '.$connection->p2p_to);
	}
	
	function updateRunnersHouse($p2p_type,$from,$to)
	{
		if(p2p_connection_exists($p2p_type,array($from,$to)))
		{
			$p2p_id = p2p_get_connections($p2p_type,array($from,$to));
			$re = p2p_update_meta($p2p_id, $from, $to);
		}
		else
		{
			$re = p2p_create_connection($p2p_type,array($from,$to));
		}
		if ( is_wp_error($re) )
			$this->displayAndLog($re->get_error_message());
		return $re;
	}
	
}