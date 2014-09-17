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
			'SELECT d.*,count(ls.leagueparticipant) as count FROM wp_bhaa_division d
			JOIN wp_bhaa_leaguesummary ls on ls.leaguedivision=d.code
			WHERE ls.league=%d
			AND ls.leaguescorecount>=2
			AND d.code=%s',$this->getLeagueId(),$division);
		//error_log($this->getLeagueId().' '.$query);
		return $this->getWpdb()->get_results($divisionDetailsSQL);
	}
	
	function getLeagueRaces($type='') {
		$SQL = $this->wpdb->prepare("select l.ID as lid,l.post_title,
			e.ID as eid,e.post_title as etitle,LEFT(e.post_title,8) as etag,eme.event_start_date as edate,e.guid as eurl,
			r.ID as rid,r.post_title as rtitle,r_type.meta_value as rtype
			from wp_posts l
			inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
			inner join wp_posts e on (e.id=l2e.p2p_to)
			inner join wp_em_events eme on (eme.post_id=e.id)
			inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
			inner join wp_posts r on (r.id=e2r.p2p_to)
			inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
			where l.post_type='league'
			and l.ID=%d", $this->leagueid);
		if($type!='')
			$SQL .= sprintf(" and r_type.meta_value in ('C','S','%s') AND r_type.meta_value!='TRACK'",$type);
		$SQL .= ' order by eme.event_start_date ASC';
		return $this->wpdb->get_results($SQL,OBJECT);
	}
}
?>