
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
left join wp_bhaa_teamsummary ts on (rd.race=ts.race and ts.team=161)
where rd.league=3709
and rd.racetype in ('C','S','M') 
and rd.racetype!='TRACK'
order by rd.eventdate asc

select GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY rd.eventdate SEPARATOR ',')
from wp_bhaa_race_detail rd
left join wp_bhaa_teamsummary ts on (rd.race=ts.race and ts.team=161)
where rd.league=3709
and rd.racetype in ('C','S','W') 
and rd.racetype!='TRACK'
order by rd.eventdate asc

SELECT * FROM wp_bhaa_leaguesummary
WHERE league=3709
AND leaguedivision='W'

CALL getLeagueTeamSummary(161,3709,'M');
call getLeagueTeamSummary(161,3709,'W');
