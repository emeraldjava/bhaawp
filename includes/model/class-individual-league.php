<?php
/**
 * Individual league
 * @author oconnellp
 */
class IndividualLeague extends BaseModel implements League {
	
	private $leagueid;
	
	function __construct($leagueid) {
		$this->leagueid=$leagueid;
	}
	
	public function getName() {
		return 'IndividualLeague';
	}
	
	/**
	 * Delete the league data
	 */
	public function deleteLeague(){
		error_log("deleteIndividualLeague(".$this->leagueid.')');
	}
	
	/**
	 * Load the league data
	*/
	public function loadLeague() {
		$res = $this->wpdb->query($this->wpdb->prepare('call updateLeagueData(%d)',$this->leagueid));
		error_log("updateLeagueData(".$this->leagueid.')-->'.$res);
	}
}
?>