
-- 3615
select id,post_title from wp_posts where post_type="league";

select * from wp_bhaa_division
-- select the men/womens races in a league
select * from wp_bhaa_race_detail 
where league=3615
and racetype in ("C","M");

select * from wp_bhaa_leaguesummary where league=3615;
delete from wp_bhaa_leaguesummary where league=3615;

-- populate league summary table
INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
leaguedivision,leagueposition,leaguesummary)
select 
l.league as league,
'I' as leaguetype,
ts.team as leagueparticipant,
ts.teamname as name,
ROUND(AVG(ts.totalstd),0) as leaguestandard,
COUNT(ts.race) as leaguescorecount,
ROUND(SUM(ts.leaguepoints),0) as leaguepoints,
'M' as leaguedivision,
1 as leagueposition,
GROUP_CONCAT( cast( concat_ws(':',l.event,ts.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
from wp_bhaa_race_detail l
join wp_bhaa_teamsummary ts on l.race=ts.race
where league=3615
and racetype in ("C","M")
GROUP BY l.league,ts.team
ORDER BY leaguepoints desc,leaguescorecount desc;

-- TODO update league position & league summary

-- get the order of events/races in a league
select l.race,l.event,l.eventname,l.eventdate
from wp_bhaa_race_detail l
where league=3615
and racetype in ("C","M")
order by eventdate asc

-- get the specific results for a team
select ts.*,l.* from wp_bhaa_teamsummary ts
join wp_bhaa_race_detail l on (l.race=ts.race and l.racetype in ("C","M") and l.league=3615)
where ts.team=97 

-- return 0 where team didn't run
select l.race,l.event,l.eventname,l.eventdate,IFNULL(ts.leaguepoints,0) as points
from wp_bhaa_race_detail l
left join wp_bhaa_teamsummary ts on (l.race=ts.race and ts.team=97)
where league=3615
and racetype in ("C","M")
order by eventdate asc

-- working team summary string
select GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',') as sumary
from wp_bhaa_race_detail l
left join wp_bhaa_teamsummary ts on (l.race=ts.race and ts.team=97)
where league=3615
and racetype in ("C","M")
order by eventdate asc

update wp_bhaa_leaguesummary ls
set ls.leaguesummary=(
select GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',')
from wp_bhaa_race_detail l
left join wp_bhaa_teamsummary ts on (l.race=ts.race and ts.team=ls.leagueparticipant)
where l.league=ls.league
and l.racetype in ("C","M")
order by l.eventdate asc
)
where ls.league=3615 and ls.leaguedivision='M'
order by ls.leaguepoints desc

-- display the league table
select * from wp_bhaa_leaguesummary
where league=3615 and leaguedivision='M'
order by leaguepoints desc

select ls.leagueparticipant,ls.leaguepoints,ls.leaguesummary from wp_bhaa_leaguesummary ls
where ls.league=3615 and ls.leaguedivision='M'
order by ls.leaguepoints desc


