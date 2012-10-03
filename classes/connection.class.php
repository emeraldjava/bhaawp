<?php
class Connection
{
	const EVENT_TO_RACE = 'event_to_race';
	const LEAGUE_TO_EVENT = 'league_to_event';
	const HOUSE_TO_RUNNER = 'house_to_runner';
	const SECTORTEAM_TO_RUNNER = 'sectorteam_to_runner';
		
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
		add_action('p2p_created_connection',array($this,'bhaa_p2p_created_connection'));
	}
	
	/**
	 * https://github.com/scribu/wp-posts-to-posts/issues/236
	 * @param unknown_type $p2p_id
	 */
	function bhaa_p2p_created_connection($p2p_id)
	{
		//$connection = p2p_get_connection( $p2p_id );
		// 		if ( 'some-ctype-name' == $connection->p2p_type ) {
		// 			// do things
		// 		}
		error_log('bhaa_p2p_created_connection() '.$p2p_id);
	}
	
}