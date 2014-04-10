
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

select ts.*,l.* from wp_bhaa_teamsummary ts
join wp_bhaa_race_detail l on (l.race=ts.race and l.racetype in ("C","M") and l.league=3615)
where ts.team=97 

GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',') as s
GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',')
-- summarize the teams league results
select ts.leaguepoints,l.eventname,l.racetype,l.race

	from wp_bhaa_race_detail l
	join wp_bhaa_teamsummary ts on (l.race=ts.race and ts.team=97)
	where l.race IN (
		select il.race
		from wp_bhaa_race_detail il
		where il.league=3615
		and il.racetype in ("C","M")
		order by il.eventdate asc)
	group by l.race,ts.team

--select l.race,l.event,l.eventname,ts.leaguepoints
select GROUP_CONTACT(IFNULL(ts.leaguepoints,0)) as su
from wp_bhaa_race_detail l
left join wp_bhaa_teamsummary ts on (ts.race=l.race and l.racetype in ("C","M"))
where league=3615
and racetype in ("C","M")

order by eventdate asc

select * from wp_bhaa_teamsummary where league=3615 and team=97;



select GROUP_CONCAT(CAST(CONCAT(IFNULL(ts.leaguepoints,0)) AS CHAR) ORDER BY l.eventdate SEPARATOR ',')
from wp_bhaa_race_detail l
left join wp_bhaa_teamsummary ts on (l.race=ts.race )
where l.race IN (
	select l.race
	from wp_bhaa_race_detail l
	where league=3615
	and racetype in ("C","W")
	order by eventdate asc
);

select GROUP_CONCAT(CAST(CONCAT(IFNULL(rr.leaguepoints,0)) AS CHAR) ORDER BY eme.event_start_date SEPARATOR ',')
	from wp_posts p
	left join wp_bhaa_teamresult rr on (p.id=rr.race and rr.team=_team)
	join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=p.id)
	join wp_em_events eme on (eme.post_id=e2r.p2p_from)
	where p.ID IN (
	select r.ID from wp_posts l
	inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
	inner join wp_posts e on (e.id=l2e.p2p_to)
	inner join wp_em_events eme on (eme.post_id=e.id)
	inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
	inner join wp_posts r on (r.id=e2r.p2p_to)
	inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
	where l.post_type='league'
	and l.ID=_leagueId and r_type.meta_value in ('C','S',_gender) 
AND r_type.meta_value!='TRACK' 
order by eme.event_start_date ASC)

	
select l.event,ts.*
from wp_bhaa_race_detail l
outer join wp_bhaa_teamsummary ts on l.race=ts.race
where league=3615
and racetype in ("C","M")
and ts.team=97


select * from wp_bhaa_teamsummary where race=3102

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
leaguedivision,leagueposition,leaguesummary)
SELECT
le.id,
'I',
rr.runner as leagueparticipant,
ROUND(AVG(rr.standard),0),
COUNT(rr.race),
ROUND(getLeaguePointsTotal(le.id,rr.runner),1) as leaguepoints,
'A',
1,
GROUP_CONCAT( cast( concat_ws(':',e.ID,rr.leaguepoints,IF(class='RACE_ORG','RO',NULL)) AS char ) SEPARATOR ',') AS leaguesummary
FROM wp_bhaa_raceresult rr
inner join wp_posts r ON rr.race = r.id
inner join wp_postmeta rt on (rt.post_id=r.id and rt.meta_key = 'bhaa_race_type')
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
inner join wp_posts e ON e2r.p2p_from = e.id
inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
inner JOIN wp_posts le ON l2e.p2p_from = le.id
inner JOIN wp_users ru ON rr.runner = ru.id
JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key = 'bhaa_runner_status')
JOIN wp_usermeta standard ON (standard.user_id=rr.runner AND standard.meta_key = 'bhaa_runner_standard')
WHERE le.id=3615 AND class in ('RAN','RACE_ORG') 
AND standard.meta_value IS NOT NULL AND status.meta_value='M'
AND rt.meta_value!='TRACK' -- exclude TRACK events
GROUP BY le.id,rr.runner
HAVING COALESCE(leaguepoints, 0) > 0;