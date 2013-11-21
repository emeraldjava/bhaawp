<?php
class LeagueSummary extends BaseModel implements Table
{
	private $leagueid;
	private $type;
	
	function __construct($leagueid) {
		parent::__construct();
		$this->leagueid=$leagueid;
		$this->type = get_post_meta($this->leagueid,LeagueCpt::BHAA_LEAGUE_TYPE,true);
	}
	
	function getType() {
		return $this->type;
	}
	
	function getName() {
		return 'wp_bhaa_leaguesummary';
	}
	
	function getLinkType() {
		if($this->getType()=='T')
			return 'house/?p';
		else 
			return 'runner/?id';
	}
	
	function getCreateSQL()
	{
		return "
			league int(10) unsigned NOT NULL,
			leaguetype enum('I','T') NOT NULL,
			leagueparticipant int(10) unsigned NOT NULL,
			leaguestandard int(10) unsigned NOT NULL,
			leaguedivision varchar(5) NOT NULL,
			leagueposition int(10) unsigned NOT NULL,
			leaguescorecount int(10) unsigned NOT NULL,
			leaguepoints double NOT NULL,
			leaguesummary varchar(500),
			PRIMARY KEY (leaguetype, league, leagueparticipant, leaguedivision) USING BTREE";
	}
	
	function getDivisions()
	{
		//$type = get_post_meta($this->leagueid,LeagueCpt::BHAA_LEAGUE_TYPE,true);
		//error_log('getDivisions() '.$type);
		if($this->type == 'I')
		{
			$SQL = $this->wpdb->prepare("select * from wp_bhaa_division where type=%s",$this->type);
 			//error_log($SQL);
			return $this->wpdb->get_results($SQL,OBJECT);
		}
		else
		{
			$SQL = $this->wpdb->prepare("select * from wp_bhaa_division where ID in (%s)","9,13");
			//error_log($SQL);
			return $this->wpdb->get_results($SQL,OBJECT);
			//return array('M','W');
		}
	}

	function getLeagueRaces($type='')
	{
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
		//echo $SQL;
		//error_log($SQL);
		// OBJECT, OBJECT_K, ARRAY_A, ARRAY_N
		return $this->wpdb->get_results($SQL,OBJECT);
	}

	function getRaceIdSetString($races)
	{
		// $rid_array = array_map(function($val) { return $val->rid; } ,$races);
		return implode(',',array_map(array($this,'rid_mapper'), $races) );
		//echo print_r($rid_array,true).PHP_EOL;
	}
	
	private function rid_mapper($val) {
		return $val->rid;
	}
	
	// return a summary of the top x in each division
	function getLeagueSummaryByDivision($limit=10)
	{
		global $wpdb;
		//error_log('getLeagueSummaryByDivision '.$this->type.' '.$this->leagueid);
		if($this->type=='I') {
			$query = $wpdb->prepare('SELECT *,wp_users.display_name as display_name
				FROM wp_bhaa_leaguesummary
				join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
				WHERE league = %d
				AND leagueposition <= %d
				AND leaguetype = %s
				order by league, leaguedivision, leagueposition',$this->leagueid,$limit,$this->type);
		} else {
			$query = $wpdb->prepare('SELECT *,wp_posts.post_title as display_name
				FROM wp_bhaa_leaguesummary
				left join wp_posts on (wp_posts.id=wp_bhaa_leaguesummary.leagueparticipant and wp_posts.post_type="house")
				WHERE league = %d
				AND leagueposition <= %d
				AND leaguetype = %s
				order by league, leaguedivision, leagueposition',$this->leagueid,$limit,$this->type);
		}
		error_log($this->type.' '.$this->leagueid.' '.$query);
		return $wpdb->get_results($query);
	}
	
	// get the specific of a league division and limit
	function getDivisionSummary($division,$limit=100) {
		if($this->type=='I') {
			$SQL = $this->wpdb->prepare('select wp_bhaa_leaguesummary.*,wp_users.display_name,wp_posts.ID,wp_posts.post_title from wp_bhaa_leaguesummary
				left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
				left join wp_posts on wp_posts.post_type="house" and wp_posts.id=
					(select meta_value from wp_usermeta where user_id=wp_bhaa_leaguesummary.leagueparticipant and meta_key="bhaa_runner_company")
				where league=%d and leaguedivision=%s and leagueposition<=%d and leaguescorecount>=1 order by leaguepoints desc',$this->leagueid,$division,$limit);
		} else {
			$SQL = $this->wpdb->prepare('select wp_bhaa_leaguesummary.*,wp_posts.post_title as display_name,wp_posts.ID,wp_posts.post_title from wp_bhaa_leaguesummary
				left join wp_posts on (wp_posts.post_type="house" and wp_posts.id=wp_bhaa_leaguesummary.leagueparticipant)
				where league=%d and leaguedivision=%s and leagueposition<=%d and leaguescorecount>=1 order by leaguepoints desc',$this->leagueid,$division,$limit);
		}
		//error_log($division.' '.$SQL);
		return $this->wpdb->get_results($SQL);
	}
	
	function getRunnerLeagueSummary($races,$runner)
	{
		// $this->wpdb->prepare
		$SQL = sprintf("select e.ID as eid,race,leaguepoints from wp_bhaa_raceresult
			inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=race)
			inner join wp_posts e on (e.id=e2r.p2p_from)
			where race in ( %s )
			and runner=%d;",$races,$runner);
		//error_log($SQL);
		return $this->wpdb->get_results($SQL);
	}
	
	/**
	 * Call the correct stored procedure to recalcualate the league summary
	 */
	function updateLeagueData() {
		if($this->type=='I') {
			$res = $this->wpdb->query($this->wpdb->prepare('call updateLeagueData(%d)',$this->leagueid));
			error_log("updateLeagueData(".$this->leagueid.')-->'.$res);
		} else {
			$res = $this->wpdb->query($this->wpdb->prepare('call updateTeamLeagueSummary(%d)',$this->leagueid));			
			error_log("updateTeamLeagueSummary(".$this->leagueid.')-->'.$res);
		} 
	}
}
?>