<?php 
/**
 * 
 * http://coderoncode.com/2014/01/19/design-patterns-php-factories.html
 * @author oconnellp
 *
 */
abstract class AbstractLeague extends BaseModel implements League {
	
	protected $leagueid;
	
	function __construct($leagueid) {
		parent::__construct();
		$this->leagueid=$leagueid;
	}
	
	function getLeagueId() {
		return $this->leagueid;
	}
	
	function getDivisionDetails($division) {
		$divisionDetailsSQL = $this->getWpdb()->prepare(
			'SELECT d.*,count(ls.leagueparticipant) FROM wp_bhaa_division d
			JOIN wp_bhaa_leaguesummary ls on ls.leaguedivision=d.code
			WHERE ls.league=%d
			AND d.code=%s',$this->getLeagueId(),$division);
		//error_log($this->getLeagueId().' '.$query);
		return $this->getWpdb()->get_results($divisionDetailsSQL);
	}
}
?>