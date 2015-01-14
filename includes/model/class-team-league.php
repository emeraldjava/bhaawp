<?php
/**
 * Team League
 * @author oconnellp
 *
 */
class TeamLeague extends AbstractLeague {
	
	function __construct($leagueid) {
		parent::__construct($leagueid);
	}
	
	function getName() {
		return 'TeamLeague';
	}
	
	function getLinkType() {
		return 'house/?p';
	}
	
	function getTopParticipantsInDivision($division,$limit) {

		$query = $this->getWpdb()->prepare('SELECT ls.*,wp_posts.post_title as display_name
			FROM wp_bhaa_leaguesummary ls
			left join wp_posts on (wp_posts.id=ls.leagueparticipant and wp_posts.post_type="house")
			WHERE ls.league = %d
			AND ls.leaguedivision = %s
			order by leaguepoints desc limit %d',$this->getLeagueId(),$division,$limit);
		//error_log($query);
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
	 * 
	 * 
		DELETE FROM wp_bhaa_teamsummary;
		DELETE FROM wp_bhaa_leaguesummary where league=3709 and leaguedivision='M';
	 */
	public function deleteLeague() {
		error_log("deleteTeamLeague(".$this->leagueid.')');
		$SQL = $this->wpdb->prepare('DELETE FROM wp_bhaa_race_detail where league=%d',$this->leagueid);
		error_log($SQL);
		$this->wpdb->query($SQL);
		//$SQL = $this->wpdb->prepare('DELETE FROM wp_bhaa_teamsummary');
		$SQL = 'DELETE FROM wp_bhaa_teamsummary WHERE race!=3147 AND race!=3148';
		error_log($SQL);
		$this->wpdb->query($SQL);
		$SQL = $this->wpdb->prepare('DELETE FROM wp_bhaa_leaguesummary WHERE league=%d',$this->leagueid);// leaguedivision="M" AND leaguedivision="W" AND
		error_log($SQL);
		$this->wpdb->query($SQL);
	}
	
	/**
	 * Load the league data
	*/
	public function loadLeague() {
		error_log("loadTeamLeague(".$this->leagueid.')');
		$SQL = $this->wpdb->prepare(
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
		//error_log($SQL);
		$this->wpdb->query($SQL);
		
		//$SQL = $this->wpdb->prepare(
		$SQL = 'INSERT INTO wp_bhaa_teamsummary
			SELECT
			race,
			team,
			teamname,
			min(totalstd) as totalstd,
			min(totalpos) as totalpos,
			class,
			min(position)as position,
			max(leaguepoints) as leaguepoints
			FROM wp_bhaa_teamresult
			WHERE position!=0
			GROUP BY race,team
			ORDER BY class,position';
		//error_log($SQL);
		$this->wpdb->query($SQL);

		// GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',') AS leaguesummary
		$SQL = $this->wpdb->prepare(
			"INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
			leaguedivision,leagueposition,leaguesummary)
			select
			l.league as league,
			'T' as leaguetype,
			ts.team as leagueparticipant,
			ROUND(AVG(ts.totalstd),0) as leaguestandard,
			COUNT(ts.race) as leaguescorecount,
			ROUND(SUM(ts.leaguepoints),0) as leaguepoints,
			'M' as leaguedivision,
			1 as leagueposition,
			GROUP_CONCAT(CAST(CONCAT_WS(':',l.event,ts.leaguepoints,IF(ts.class='RACE_ORG','RO',NULL)) AS char ) SEPARATOR ',') AS leaguesummary
			from wp_bhaa_race_detail l
			join wp_bhaa_teamsummary ts on l.race=ts.race
			where league=%d
			AND ts.class!='W'
			and racetype in ('C','M')
			GROUP BY l.league,ts.team
			ORDER BY leaguepoints desc,leaguescorecount desc",$this->leagueid);
		//error_log($SQL);
		$this->wpdb->query($SQL);
		
		$SQL = $this->wpdb->prepare(
			"INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
			leaguedivision,leagueposition,leaguesummary)
			select
			l.league as league,
			'T' as leaguetype,
			ts.team as leagueparticipant,
			ROUND(AVG(ts.totalstd),0) as leaguestandard,
			COUNT(ts.race) as leaguescorecount,
			ROUND(SUM(ts.leaguepoints),0) as leaguepoints,
			'W' as leaguedivision,
			1 as leagueposition,
			GROUP_CONCAT(CAST(CONCAT_WS(':',l.event,ts.leaguepoints,IF(ts.class='RACE_ORG','RO',NULL)) AS char ) SEPARATOR ',') AS leaguesummary
			from wp_bhaa_race_detail l
			join wp_bhaa_teamsummary ts on l.race=ts.race
			where league=%d
			AND ts.class='W'
			and racetype in ('C','W')
			GROUP BY l.league,ts.team
			ORDER BY leaguepoints desc,leaguescorecount desc",$this->leagueid);
		//error_log($SQL);
		$this->wpdb->query($SQL);
		
		// update the mens team league summary
		$SQL = $this->wpdb->prepare("update wp_bhaa_leaguesummary set leaguesummary=
			getLeagueMTeamSummary(leagueparticipant,%d) where
			league=%d and leaguedivision in ('M')",$this->leagueid,$this->leagueid);
		//error_log($SQL);
		$res = $this->wpdb->query($SQL);
		
		// update the womens team league summary
		$SQL = $this->wpdb->prepare("update wp_bhaa_leaguesummary set leaguesummary=
			getLeagueWTeamSummary(leagueparticipant,%d) where
			league=%d and leaguedivision in ('W')",$this->leagueid,$this->leagueid);
		//error_log($SQL);
		$res = $this->wpdb->query($SQL);
	}
}
?>