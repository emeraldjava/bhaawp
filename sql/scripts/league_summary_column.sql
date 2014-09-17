
call getTeamLeagueSummary(3709,161,'M');

-- list all the races for a league
SELECT *
from wp_bhaa_race_detail rd
where rd.league=3709
and rd.racetype in ('C','S','W') 
and rd.racetype!='TRACK'
order by rd.eventdate asc

-- select all the races, then left join to results for an individual
SELECT *
select GROUP_CONCAT(CAST(CONCAT(IFNULL(rr.leaguepoints,0)) AS CHAR) ORDER BY rd.eventdate SEPARATOR ',')
from wp_bhaa_race_detail rd
left join wp_bhaa_raceresult rr on (rr.race=rd.race and rr.runner=7713)
where rd.league=3709
and rd.racetype in ('C','S','M') 
and rd.racetype!='TRACK'
order by rd.eventdate asc

-- select all the races, then left join to results for a team
SELECT *
select GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY rd.eventdate SEPARATOR ',')
from wp_bhaa_race_detail rd
LEFT join wp_bhaa_teamsummary ts on (rd.race=ts.race and ts.team=161 AND ts.class!='W')
where rd.league=3709
and rd.racetype in ('C','S','M') 
and rd.racetype!='TRACK'
order by rd.eventdate asc

SELECT *
select GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY rd.eventdate SEPARATOR ',')
from wp_bhaa_race_detail rd
left join wp_bhaa_teamsummary ts on (rd.race=ts.race and ts.team=161 AND ts.class='W')
where rd.league=3709
and rd.racetype in ('C','S','W') 
and rd.racetype!='TRACK'
order by rd.eventdate asc

SELECT * FROM wp_bhaa_leaguesummary
WHERE league=3709
AND leagueparticipant=161

SELECT * FROM wp_bhaa_teamresult
WHERE team=161



AND leaguedivision='W'

SELECT getLeagueMTeamSummary(leagueparticipant,league) FROM wp_bhaa_leaguesummary 
WHERE league=3709 and leaguedivision='M' and leagueparticipant=161;

SELECT getLeagueWTeamSummary(leagueparticipant,league) FROM wp_bhaa_leaguesummary 
WHERE league=3709 and leaguedivision='W' and leagueparticipant=161;

-- BOI womans team summer league 2014

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
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
			where league=3709
			AND ts.class='W'
			and racetype in ('C','W')
			GROUP BY l.league,ts.team
			ORDER BY leaguepoints desc,leaguescorecount desc
			
-- display the total number of runners in specific league division
select d.*,count(ls.leagueparticipant) from wp_bhaa_division d
join wp_bhaa_leaguesummary ls 
on ls.leaguedivision=d.code 
where ls.league=3637
group by d.code

