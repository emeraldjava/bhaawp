DELETE FROM wp_bhaa_race_detail where league=3709;
INSERT INTO wp_bhaa_race_detail (league,leaguetype,event,eventname,eventdate,race,racetype,distance,unit)
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
join wp_p2p e2r on (l2e.p2p_to=e2r.p2p_from AND e2r.p2p_type='event_to_race')
join wp_posts race on (e2r.p2p_to=race.ID)
LEFT join wp_postmeta racetype on (race.ID=racetype.post_id AND racetype.meta_key='bhaa_race_type')
LEFT join wp_postmeta racedistance on (race.ID=racedistance.post_id AND racedistance.meta_key='bhaa_race_distance')
LEFT join wp_postmeta raceunit on (race.ID=raceunit.post_id AND raceunit.meta_key='bhaa_race_unit')
LEFT join wp_postmeta leaguetype on (l2e.p2p_from=leaguetype.post_id AND leaguetype.meta_key='bhaa_league_type')
where l2e.p2p_type='league_to_event' and l2e.p2p_from IN (3709)
ORDER BY eventdate;

-- update all team summary
DELETE FROM wp_bhaa_teamsummary;
INSERT INTO wp_bhaa_teamsummary
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
ORDER BY class,position;


DELETE FROM wp_bhaa_race_detail where league=3709
SELECT * FROM wp_bhaa_race_detail where league=3709

DELETE FROM wp_bhaa_leaguesummary WHERE league=3709 AND leaguedivision="M" AND leaguedivision="W" AND 
SELECT * FROM wp_bhaa_leaguesummary WHERE league=3709 AND leaguedivision="M" AND leaguedivision="W"

-- mens teams
DELETE FROM wp_bhaa_leaguesummary where league=3709 and leaguedivision='M';
INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
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
GROUP_CONCAT(CAST(CONCAT_WS(':',l.event,ts.leaguepoints,IF(ts.class='RACE_ORG','RO',NULL)) AS char ) SEPARATOR ",") AS leagues,
GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',') as ls,
GROUP_CONCAT( cast( concat_ws(':',l.event,ts.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
from wp_bhaa_race_detail l
join wp_bhaa_teamsummary ts on l.race=ts.race
where league=3709
AND ts.class != 'W'
and racetype in ('C','M')
GROUP BY l.league,ts.team
HAVING COALESCE(leaguepoints, 0) > 0
ORDER BY leaguepoints desc,leaguescorecount desc;

GROUP BY le.id,rr.runner


-- individual format
select * from wp_bhaa_leaguesummary
where leagueparticipant=7713;

-- womens teams
DELETE FROM wp_bhaa_leaguesummary where league=3709 and leaguedivision='W';
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
GROUP_CONCAT( cast( concat_ws(':',l.event,ts.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
from wp_bhaa_race_detail l
join wp_bhaa_teamsummary ts on l.race=ts.race
where league=3709
AND ts.class ='W'
and racetype in ('C','W')
GROUP BY l.league,ts.team
ORDER BY leaguepoints desc,leaguescorecount desc;

-- update the team summary field
update wp_bhaa_leaguesummary
set leaguesummary=getTeamLeagueSummary(leagueparticipant,league,leaguedivision)
where league=3709;

SET @a=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@a:= (@a+1)) 
where leaguedivision="M" and league=_leagueId ORDER BY leaguepoints DESC;
SET @b=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@b:= (@b+1)) 
where leaguedivision="W" and league=_leagueId ORDER BY leaguepoints DESC;



-- the league
select * from wp_bhaa_leaguesummary where league=3709 ORDER BY leaguedivision,leaguepoints DESC
-- a summary of kclub 2014 results
select * from wp_bhaa_teamsummary where race=3529
-- all kclub 2014 team results 
select * from wp_bhaa_teamresult where race=3529

select * FROM  wp_bhaa_race_detail where league=3709;

select * from wp_bhaa_teamsummary

select * from wp_bhaa_race_detail where league=3709
select * from wp_postmeta where post_id=3709

select * from wp_postmeta where meta_key='bhaa_league_type'



