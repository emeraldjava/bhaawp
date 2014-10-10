<?php
/**
 * Individual league
 * @author oconnellp
 */
class IndividualLeague extends AbstractLeague {
		
	function __construct($leagueid) {
		parent::__construct($leagueid);
	}
	
	function getName() {
		return 'IndividualLeague';
	}
	
	function getLinkType() {
		return 'runner/?id';
	}
	
	function getTopParticipantsInDivision($division,$limit) {
	//function getTopRunnersInDivision($division,$limit) {
		$query = $this->getWpdb()->prepare('SELECT ls.*,wp_users.display_name as display_name
			FROM wp_bhaa_leaguesummary ls
			LEFT join wp_users on wp_users.id=ls.leagueparticipant
			LEFT join wp_posts on wp_posts.post_type="house" and wp_posts.id=
				(select meta_value from wp_usermeta where user_id=ls.leagueparticipant and meta_key="bhaa_runner_company")
			WHERE ls.league = %d
			AND ls.leaguedivision = %s
			AND ls.leagueposition <= %d
			AND ls.leaguescorecount>=2
			order by league, leaguedivision, leagueposition',$this->getLeagueId(),$division,$limit);
		//error_log($this->getLeagueId().' '.$query);
		$summary = $this->getWpdb()->get_results($query);
		
		$divisionDetails = $this->getDivisionDetails($division);
		
		if($limit!=1000) {
			return Bhaa_Mustache::get_instance()->loadTemplate('division-summary')->render(
				array(
					'division' => $divisionDetails,
					'id'=> $this->getLeagueId(),
					'top'=> $limit,
					'url'=> get_permalink( $this->getLeagueId() ),
					'linktype' => $this->getLinkType(),
					'summary' => $summary
			));
		} else {
			
			if(strpos($division,'L'))
				$events = $this->getLeagueRaces('W');
			else
				$events = $this->getLeagueRaces('M');
				
			return Bhaa_Mustache::get_instance()->loadTemplate('division-detailed')->render(
				array(
					'division' => $divisionDetails,
					'id'=> $this->getLeagueId(),
					'top'=> $limit,
					'url'=> get_permalink( $this->getLeagueId() ),
					'summary' => $summary,
					'linktype' => $this->getLinkType(),
					'events' => $events,
					'matchEventResult' => function($text, Mustache_LambdaHelper $helper) {
						$results = explode(',',$helper->render($text));
						//error_log($helper->render($text).' '.$results);
						$row = '';
						foreach($results as $result) {
							if($result==0)
								$row .= '<td>-</td>';
							else
								$row .= '<td>'.$result.'</td>';
						}
						return $row;
					}
			));
		}
	}
	
	/**
	 * Delete the league data
	 */
	public function deleteLeague() {
		
		$SQL = $this->getWpdb()->prepare('DELETE FROM wp_bhaa_race_detail where league=%d',$this->leagueid);
		error_log($SQL);
		$this->getWpdb()->query($SQL);
		
		$SQL = $this->getWpdb()->prepare('DELETE FROM wp_bhaa_leaguesummary WHERE league=%d',$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		queue_flash_message('Delete league content '.$this->leagueid);
	}
	
	/**
	 * Load the league data
	*/
	public function loadLeague() {
		
		$SQL = $this->getWpdb()->prepare(
			'INSERT INTO wp_bhaa_race_detail (league,leaguetype,event,eventname,eventdate,race,racetype,distance,unit)
			select
			l2e.p2p_from as league,
			leaguetype.meta_value as leaguetype,
			event.ID as event,
			event.post_title as eventname,
			em.event_start_date as eventdate,
			race.ID as race,
			racetype.meta_value as racetype,
			racedistance.meta_value as distance,
			raceunit.meta_value as raceunit
			from wp_p2p l2e
			join wp_posts event on (l2e.p2p_to=event.ID)
			join wp_em_events em on (event.id=em.post_id)
			join wp_p2p e2r on (l2e.p2p_to=e2r.p2p_from AND e2r.p2p_type="event_to_race")
			join wp_posts race on (e2r.p2p_to=race.ID)
			LEFT join wp_postmeta racetype on (race.ID=racetype.post_id AND racetype.meta_key="bhaa_race_type")
			LEFT join wp_postmeta racedistance on (race.ID=racedistance.post_id AND racedistance.meta_key="bhaa_race_distance")
			LEFT join wp_postmeta raceunit on (race.ID=raceunit.post_id AND raceunit.meta_key="bhaa_race_unit")
			LEFT join wp_postmeta leaguetype on (l2e.p2p_from=leaguetype.post_id AND leaguetype.meta_key="bhaa_league_type")
			where l2e.p2p_type="league_to_event" and l2e.p2p_from IN (%d)
			ORDER BY eventdate',$this->leagueid);
		error_log($SQL);
		$this->getWpdb()->query($SQL);
		
		$SQL = $this->getWpdb()->prepare(
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
		$res = $this->getWpdb()->query($SQL);
		
		// set the divisions
		$SQL = $this->getWpdb()->prepare('UPDATE wp_bhaa_leaguesummary
			JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant AND gender.meta_key="bhaa_runner_gender")
			JOIN wp_bhaa_division d ON ((wp_bhaa_leaguesummary.leaguestandard BETWEEN d.min AND d.max) AND d.type="I" and d.gender=gender.meta_value)
			set wp_bhaa_leaguesummary.leaguedivision=d.code
			where league=%d',$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		
		$this->getWpdb()->query("SET @a=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@a:=(@a+1)) 
			where leaguedivision='A' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @b=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@b:=(@b+1))
			where leaguedivision='B' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @c=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@c:=(@c+1))
			where leaguedivision='C' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @d=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@d:=(@d+1))
			where leaguedivision='D' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @e=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@e:=(@e+1))
			where leaguedivision='E' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @f=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@f:=(@f+1))
			where leaguedivision='F' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @g=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@g:=(@g+1))
			where leaguedivision='L1' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		$this->getWpdb()->query("SET @h=0");
		$SQL = $this->getWpdb()->prepare("UPDATE wp_bhaa_leaguesummary SET leagueposition=(@h:=(@h+1))
			where leaguedivision='L2' and league=%d ORDER BY leaguepoints DESC",$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		
		// update the league summary
		$SQL = $this->getWpdb()->prepare("update wp_bhaa_leaguesummary set leaguesummary=
			getLeagueRunnerSummary(leagueparticipant,%d,'M') where
			league=%d and leaguedivision in ('A','B','C','D','E','F')",$this->leagueid,$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		
		$SQL = $this->getWpdb()->prepare("update wp_bhaa_leaguesummary set leaguesummary=
			getLeagueRunnerSummary(leagueparticipant,%d,'W') where
			league=%d and leaguedivision in ('L1','L2')",$this->leagueid,$this->leagueid);
		error_log($SQL);
		$res = $this->getWpdb()->query($SQL);
		
		queue_flash_message('Updated league content '.$this->leagueid);
	}
}
?>