<?php
/**
 * Team League
 * @author oconnellp
 *
 */
class TeamLeague extends BaseModel implements League {
	
	private $leagueid;
	
	function __construct($leagueid) {
		$this->leagueid=$leagueid;
	}
	
	public function getName() {
		return 'TeamLeague';
	}
	
	/**
	 * Delete the league data
	 */
	public function deleteLeague(){
		error_log("deleteTeamLeague(".$this->leagueid.')');
	}
	
	/**
	 * Load the league data
	*/
	public function loadLeague(){
		error_log("loadTeamLeague(".$this->leagueid.')');
	}
	
}
?>