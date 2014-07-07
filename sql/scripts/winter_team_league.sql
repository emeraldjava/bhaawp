
-- 3615
select id,post_title from wp_posts where post_type="league";

select * from wp_bhaa_division
-- select the men/womens races in a league
select * from wp_bhaa_race_detail 
where league=3615
and racetype in ("C","M");

select * from wp_bhaa_leaguesummary where league=3615;
delete from wp_bhaa_leaguesummary where league=3615;

-- populate individual league summary table
INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
leaguedivision,leagueposition,leaguesummary)
select 
l.league as league,
'I' as leaguetype,
ts.team as leagueparticipant,
ROUND(AVG(ts.totalstd),0) as leaguestandard,
COUNT(ts.race) as leaguescorecount,
ROUND(SUM(ts.leaguepoints),0) as leaguepoints,
'M' as leaguedivision,
1 as leagueposition,
GROUP_CONCAT( cast( concat_ws(':',l.event,ts.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
from wp_bhaa_race_detail l
join wp_bhaa_teamsummary ts on l.race=ts.race
where league=3615
and racetype in ('C','M')
GROUP BY l.league,ts.team
ORDER BY leaguepoints desc,leaguescorecount desc;

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
leaguedivision,leagueposition,leaguesummary)
select 
l.league as league,
'I' as leaguetype,
ts.team as leagueparticipant,
ROUND(AVG(ts.totalstd),0) as leaguestandard,
COUNT(ts.race) as leaguescorecount,
ROUND(SUM(ts.leaguepoints),0) as leaguepoints,
'W' as leaguedivision,
1 as leagueposition,
GROUP_CONCAT( cast( concat_ws(':',l.event,ts.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
from wp_bhaa_race_detail l
join wp_bhaa_teamsummary ts on l.race=ts.race
where league=3615
and racetype in ('C','W')
GROUP BY l.league,ts.team
ORDER BY leaguepoints desc,leaguescorecount desc;

update wp_bhaa_leaguesummary
set leaguesummary=getTeamLeagueSummary(leagueparticipant,league,leaguedivision)
where league=;

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

-- this don't work!
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
select league,leagueparticipant,leaguepoints,leaguesummary from wp_bhaa_leaguesummary
where league=3615 and leaguedivision='M'
order by leaguepoints desc

getTeamLeagueSummary(97,3615,'M');

select league,leagueparticipant,leaguepoints,getTeamLeagueSummary(leagueparticipant,league,'M') as s
from wp_bhaa_leaguesummary
where league=3615 and leaguedivision='M'
order by leaguepoints desc

select league,leagueparticipant,leaguepoints,getTeamLeagueSummary(leagueparticipant,league,'W') as s
from wp_bhaa_leaguesummary
where league=3615 and leaguedivision='W'
order by leaguepoints desc

select league,leaguedivision,leagueparticipant,leaguepoints,
getTeamLeagueSummary(leagueparticipant,league,leaguedivision) as s
from wp_bhaa_leaguesummary
where league=3615
order by leaguedivision,leaguepoints desc

update wp_bhaa_leaguesummary
set leaguesummary=getTeamLeagueSummary(leagueparticipant,league,leaguedivision)
where league=3615;

-- top ten per division
select leagueparticipant,u.display leagueposition,leaguedivision from wp_bhaa_leaguesummary ls
join wp_users u on ls.leagueparticipant=u.ID
where league=3103
and leagueposition<=10
order by leaguedivision,leagueposition

select leaguedivision,leagueposition,leaguepoints,u.user_nicename,fn.meta_value,
ln.meta_value,u.user_email,mobile.meta_value as mobile from wp_bhaa_leaguesummary 
join wp_users u on u.id=leagueparticipant
left JOIN wp_usermeta mobile ON (mobile.user_id=u.id AND mobile.meta_key='bhaa_runner_mobilephone')
left JOIN wp_usermeta fn ON (fn.user_id=u.id AND fn.meta_key='first_name')
left JOIN wp_usermeta ln ON (ln.user_id=u.id AND ln.meta_key='last_name')
where league=3103
and leagueposition<=10
order by leaguedivision,leagueposition






