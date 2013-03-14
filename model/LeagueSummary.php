<?php
class LeagueSummary extends BaseModel implements Table
{
	private $leagueid;
	
	function __construct($leagueid)
	{
		parent::__construct();
		$this->leagueid=$leagueid;
	}
	
	function getName()
	{
		return $this->wpdb->prefix.'bhaa_leaguesummary';
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
	{}

	function getLeagueRaces($type='')
	{
		$SQL = $this->wpdb->prepare("select l.ID as lid,l.post_title,
			e.ID as eid,e.post_title as etitle,eme.event_start_date as edate,
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
			$SQL .= sprintf(" and r_type.meta_value in ('C','%s')",$type);
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
	function getLeagueSummary($limit=10)
	{
		global $wpdb;
		$query = $wpdb->prepare('
			SELECT *,wp_users.display_name
			FROM wp_bhaa_leaguesummary
			left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
			WHERE leaguetype = "I"
			AND leagueposition <= %d
			AND league = %d
			order by league, leaguedivision, leagueposition',$limit,$this->leagueid);
		//error_log($query);
		$this->items = $wpdb->get_results($query);
		return $this->items;	
	}
	
	// get the specific of a league division
	function getDivisionSummary($division) // limit - all or 10?
	{
		$SQL = $this->wpdb->prepare('select wp_bhaa_leaguesummary.*,wp_users.display_name,wp_posts.ID,wp_posts.post_title from wp_bhaa_leaguesummary
			left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
			left join wp_posts on wp_posts.post_type="house" and wp_posts.id=
				(select meta_value from wp_usermeta where user_id=wp_bhaa_leaguesummary.leagueparticipant and meta_key="bhaa_runner_company")
			where league=%d and leaguedivision=%s and leaguescorecount>=2 order by leagueposition',$this->leagueid,$division);
		//error_log($SQL);
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
		error_log($SQL);
		return $this->wpdb->get_results($SQL);
	}
		
	function updateLeague($division='L1')
	{
		// get all league races
		$mens_races = $this->getRaceIdSetString($this->getLeagueRaces('M'));
		$womens_races = $this->getRaceIdSetString($this->getLeagueRaces('W'));
		
		// for each of the runners - update the league details
		$runners = $this->wpdb->get_results(
			$this->wpdb->prepare('select leagueparticipant,gender.meta_value as gender from wp_bhaa_leaguesummary
				join wp_usermeta gender on (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant and gender.meta_key="bhaa_runner_gender")
				where league=%d and leaguedivision=%s',$this->leagueid,$division)
		);
		//error_log(print_r($runners,true));
		foreach($runners as $runner)
		{
			$id = $runner->leagueparticipant;
			if($runner->gender=='W')
				$runner_summary = json_encode($this->getRunnerLeagueSummary($womens_races,$id),JSON_FORCE_OBJECT);
			else
				$runner_summary = json_encode($this->getRunnerLeagueSummary($mens_races,$id),JSON_FORCE_OBJECT);
			error_log($id.' '.$runner_summary);
			$runners = $this->wpdb->query(
				$this->wpdb->prepare(
					'update wp_bhaa_leaguesummary set leaguesummary=%s where leagueparticipant=%d',
					$runner_summary,
					$id)
			);
		}			
	}
}
?>