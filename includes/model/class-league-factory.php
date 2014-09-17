<?php 
/**
 * Return the appropriate individual or team league type
 * @author oconnellp
 */
class LeagueFactory {

	public static function getLeague($leagueid) {
		$type = get_post_meta($leagueid,LeagueCpt::BHAA_LEAGUE_TYPE,true);
		if($type=='I')
			return new IndividualLeague($leagueid);
		else
			return new TeamLeague($leagueid);
	}
}
?>