<?php
/**
 * Represents a BHAA event.
 * @author oconnellp
 */
class Event {
	
	private $post_id;
	
	function __construct($post_id) {
		$this->post_id = $post_id;
	}

	/**
	 * @return array of race id's.
	 */
	function getRaces() {
		$races = p2p_get_connections(Connections::EVENT_TO_RACE,
				array('direction'=>'from','from'=>$this->post_id,'type'=>'race','fields'=>'p2p_to'));
		return array_values($races);
	}
	
	function getIndividualResultsTable() {
		$races = $this->getRaces();
		$response = '';
		foreach($races as $race){
			$response .= RaceResult_List_Table::get_instance()->renderTable($race);
		}
		return $response;
	}
	
	function getTeamResultsTable() {
		$races = $this->getRaces();
		$response = '';
		foreach($races as $race){
			$teamResult = new TeamResult($race);
			$response .= $teamResult->getRaceTeamResultTable();
		}
		return $response;
	}
}
?>