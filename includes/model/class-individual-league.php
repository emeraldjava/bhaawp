<?php
/**
 * Individual league
 * @author oconnellp
 */
class IndividualLeague extends BaseModel implements League {
	
	private $leagueid;
	
	function __construct($leagueid) {
		parent::__construct();
		$this->leagueid=$leagueid;
	}
	
	public function getName() {
		return 'IndividualLeague';
	}
	
	/**
	 * Delete the league data
	 */
	public function deleteLeague() {
		$SQL = $this->wpdb->prepare('DELETE FROM wp_bhaa_leaguesummary WHERE league=%d',$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		queue_flash_message('Delete league content '.$this->leagueid);
	}
	
	/**
	 * Load the league data
	*/
	public function loadLeague() {
		$SQL = $this->wpdb->prepare(
		'INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
			leaguedivision,leagueposition,leaguesummary)
			SELECT
			le.id,
			"I",
			rr.runner as leagueparticipant,
			ROUND(AVG(rr.standard),0) as leaguestandard, 
			COUNT(rr.race) as leaguescorecount,
			ROUND(getLeaguePointsTotal(le.id,rr.runner),1) as leaguepoints,
			"A" as leaguedivision,
			1 as leagueposition,
			GROUP_CONCAT( cast( concat_ws(":",e.ID,rr.leaguepoints,IF(class="RACE_ORG","RO",NULL)) AS char ) SEPARATOR ",") AS leaguesummary
			FROM wp_bhaa_raceresult rr
			inner join wp_posts r ON rr.race = r.id
			inner join wp_postmeta rt on (rt.post_id=r.id and rt.meta_key = "bhaa_race_type")
			inner join wp_p2p e2r on (e2r.p2p_type="event_to_race" and e2r.p2p_to=r.ID)
			inner join wp_posts e ON e2r.p2p_from = e.id
			inner join wp_p2p l2e on (l2e.p2p_type="league_to_event" and l2e.p2p_to=e.ID)
			inner JOIN wp_posts le ON l2e.p2p_from = le.id
			inner JOIN wp_users ru ON rr.runner = ru.id
			JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key = "bhaa_runner_status")
			JOIN wp_usermeta standard ON (standard.user_id=rr.runner AND standard.meta_key = "bhaa_runner_standard")
			WHERE le.id=%d AND class in ("RAN","RACE_ORG") 
			AND standard.meta_value IS NOT NULL AND status.meta_value="M"
			AND rt.meta_value!="TRACK" -- exclude TRACK events
			GROUP BY le.id,rr.runner
			HAVING COALESCE(leaguepoints, 0) > 0;',$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		
		// set the divisions
		$SQL = $this->wpdb->prepare('UPDATE wp_bhaa_leaguesummary
			JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant AND gender.meta_key="bhaa_runner_gender")
			JOIN wp_bhaa_division d ON ((wp_bhaa_leaguesummary.leaguestandard BETWEEN d.min AND d.max) AND d.type="I" and d.gender=gender.meta_value)
			set wp_bhaa_leaguesummary.leaguedivision=d.code
			where league=%d',$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		
		$this->wpdb->query("SET @a=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@a:=(@a+1)) 
			where leaguedivision='A' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @b=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@b:=(@b+1))
			where leaguedivision='B' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @c=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@c:=(@c+1))
			where leaguedivision='C' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @d=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@d:=(@d+1))
			where leaguedivision='D' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @e=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@e:=(@e+1))
			where leaguedivision='E' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @f=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@f:=(@f+1))
			where leaguedivision='F' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @g=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@g:=(@g+1))
			where leaguedivision='L1' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		$this->wpdb->query("SET @h=0");
		$SQL = $this->wpdb->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@h:=(@h+1))
			where leaguedivision='L2' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		
		// update the league summary
		$SQL = $this->wpdb->prepare("update wp_bhaa_leaguesummary set leaguesummary=
			getRunnerLeagueSummary(leagueparticipant,%d,'M') where
			league=%d and leaguedivision in ('A','B','C','D','E','F')",$this->leagueid,$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		
		$SQL = $this->wpdb->prepare("update wp_bhaa_leaguesummary set leaguesummary=
			getRunnerLeagueSummary(leagueparticipant,%d,'W') where
			league=%d and leaguedivision in ('L1','L2')",$this->leagueid,$this->leagueid);
		error_log($SQL);
		$res = $this->wpdb->query($SQL);
		
		queue_flash_message('Updated league content '.$this->leagueid);
	}
}
?>