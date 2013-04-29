
DROP TABLE IF EXISTS wp_bhaa_leaguesummary;
CREATE TABLE IF NOT EXISTS wp_bhaa_leaguesummary (
	league int(10) unsigned NOT NULL,
	leaguetype enum('I','T') NOT NULL,
	leagueparticipant int(10) unsigned NOT NULL,
	leaguestandard int(10) unsigned NOT NULL,
	leaguedivision varchar(5) NOT NULL,
	leagueposition int(10) unsigned NOT NULL,
	leaguescorecount int(10) unsigned NOT NULL,
	leaguepoints double NOT NULL,
	leaguesummary varchar(500),
	PRIMARY KEY (leaguetype, league, leagueparticipant, leaguedivision) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE wp_bhaa_leaguesummary ADD COLUMN leaguesummary varchar(500) DEFAULT NULL AFTER leaguepoints;

update wp_bhaa_leaguesummary set leaguesummary='{"0":{"eid":"2121","race":"2359","leaguepoints":"10"},"1":{"eid":"2123","race":"2362","leaguepoints":"10"}}';
update wp_bhaa_leaguesummary set leaguesummary=NULL;

select * from wp_bhaa_leaguesummary where league=11 and leaguedivision=

INSERT INTO wp_bhaa_import (id, tag, type, new, old) VALUES
(NULL, 'winter2013', 'league', 2492, 13);

select * from wp_posts where post_type='league';
select * from wp_posts where post_type='event';

select l.ID as lid,l.post_title,
e.ID as eid,e.post_title as etitle,
r.ID as rid,r.post_title as rtitle,r_type.meta_value as rtype 
from wp_posts l
inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
inner join wp_posts e on (e.id=l2e.p2p_to)
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
inner join wp_posts r on (r.id=e2r.p2p_to)
inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
where l.post_type='league'
and l.ID=2492
and r_type.meta_value in ('C','M')

--inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
select runner,race,leaguepoints,e.ID as eid,e.post_title as etitle from wp_bhaa_raceresult 
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=race)
inner join wp_posts e on (e.id=e2r.p2p_from)
where race in (2358,2359,2360,2362)
and runner=7713;

SELECT   wp_posts.*, wp_p2p.* FROM wp_posts  
INNER JOIN wp_p2p WHERE 1=1  AND wp_posts.post_type IN ('league') 
AND (wp_posts.post_status = 'publish') AND (wp_p2p.p2p_type = 'league_to_event' 
AND wp_posts.ID = wp_p2p.p2p_from AND wp_p2p.p2p_to 
IN (SELECT   wp_posts.ID FROM wp_posts  WHERE 1=1  AND wp_posts.ID IN (0) 
AND wp_posts.post_type IN ('event') AND (wp_posts.post_status = 'publish')  
ORDER BY wp_posts.post_date DESC ))  ORDER BY wp_posts.post_date DESC



SELECT rr.runner, rr.race, rr.leaguepoints,
mrr.runner, mrr.race, mrr.points
FROM wp_bhaa_raceresult rr
LEFT JOIN wp_bhaa_import i ON ( i.type = 'race' AND i.new = rr.race ) 
JOIN bhaaie_members.raceresult mrr ON ( mrr.race = i.old AND mrr.runner = rr.runner ) 
AND rr.race >=1783
AND rr.runner=7713

update wp_bhaa_raceresult rr
left join wp_bhaa_import i on (i.type='race' and i.new=rr.race)
left join bhaaie_members.raceresult mrr on (mrr.race=i.old and mrr.runner=rr.runner)
set leaguepoints=points
where rr.race>=1783 and rr.runner=7713;

call procedure updateRaceScoringSets(2499);


select * from bhaaie_members.league

select * from wp_bhaa_raceresult where runner=7713;

select * from wp_bhaa_raceresult where runner=7713;

select * from bhaaie_members.racepointsdata where runner=7713;

select * from bhaaie_members.racepoints where runner=7713;

select * from bhaaie_members.leaguesummary where leagueid=13;
update wp_bhaa_leaguesummary set league=2492 where league=13;

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguedivision,leagueposition,leaguescorecount,leaguepoints)
select
2492,"I",leagueparticipantid,leaguestandard,leaguedivision,leagueposition,leaguescorecount,leaguepoints
from bhaaie_members.leaguesummary where leagueid=13;



select event.tag,
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and class NOT IN ('RO','W','WO')) as total,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='A' group by race,class) as Amin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='A' group by race,class) as Amax,
getTeamStandardQuartile(1,race.id) as 'AQ1',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='A' group by race,class) as Ateams,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='B' group by race,class) as Bmin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='B' group by race,class) as Bmax,
getTeamStandardQuartile(2,race.id) as 'BQ2',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='B' group by race,class) as Bteams,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='C' group by race,class) as Cmin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='C' group by race,class) as Cmax,
getTeamStandardQuartile(3,race.id) as 'CQ3',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='C' group by race,class) as Cteams,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='D' group by race,class) as Dmin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='D' group by race,class) as Dmax,
getTeamStandardQuartile(4,race.id) as 'DQ4',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='D' group by race,class) as Dteams
from race
join event on event.id=race.event
where race.id > 2010 and race.type IN ('M','C') and event.type != "track";
where race.id between 201100 and 201199 and race.type IN ('M','C') and event.type != "track";

DROP TABLE IF EXISTS wp_bhaa_leaguerunnerdata;
CREATE TABLE IF NOT EXISTS wp_bhaa_leaguerunnerdata (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  league int(11) unsigned NOT NULL,
  runner int(11) unsigned NOT NULL,
  racesComplete int(11) unsigned NOT NULL,
  pointsTotal double DEFAULT NULL,
  avgOverallPosition double NOT NULL,
  standard int(11) DEFAULT NULL,
  PRIMARY KEY (id)
); ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT;

call procedure getLeaguePointsTotal(2492,7713);

SELECT DISTINCT e.id,
  CASE rr.leaguepoints WHEN 11 THEN 10 ELSE rr.leaguepoints END AS points
  FROM wp_bhaa_raceresult rr
  inner join wp_posts r ON rr.race = r.id
  inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
  inner join wp_posts e ON e2r.p2p_from = e.id
  inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
  inner JOIN wp_posts le ON l2e.p2p_from = le.id
  WHERE runner=7713 AND le.id=2492
  and rr.class in ('RAN', 'RACE_ORG', 'RACE_POINTS') order by rr.leaguepoints desc

delete from wp_bhaa_leaguesummary
  
call updateLeagueData(2492);

call updateLeagueData(2659);

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,leaguedivision,leagueposition)
select
2659,"I",runner,standard,racesComplete,ROUND(pointsTotal,1),'A',1
from wp_bhaa_leaguerunnerdata
where runner=7649

DELETE FROM wp_bhaa_leaguesummary WHERE league=2659;
SELECT * FROM wp_bhaa_leaguesummary WHERE league=2659;

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,leaguedivision,leagueposition)
  SELECT
  le.id,
  'I',
  rr.runner,
  ROUND(AVG(rr.standard),0),
  COUNT(rr.race),
  ROUND(getLeaguePointsTotal(le.id,rr.runner),1) as leaguepoints,
  'A',
  1
  FROM wp_bhaa_raceresult rr
  inner join wp_posts r ON rr.race = r.id
  inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
  inner join wp_posts e ON e2r.p2p_from = e.id
  inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
  inner JOIN wp_posts le ON l2e.p2p_from = le.id
  inner JOIN wp_users ru ON rr.runner = ru.id
  JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key = 'bhaa_runner_status')
  JOIN wp_usermeta standard ON (standard.user_id=rr.runner AND standard.meta_key = 'bhaa_runner_standard')
  WHERE le.id=2659 AND class='RAN' AND standard.meta_value IS NOT NULL AND status.meta_value='M'
  GROUP BY le.id,rr.runner
  HAVING COALESCE(leaguepoints, 0) > 0;
  
update wp_bhaa_leaguesummary 
	JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant AND gender.meta_key = 'bhaa_runner_gender')
	JOIN wp_bhaa_division d ON ((wp_bhaa_leaguesummary.leaguestandard BETWEEN d.min AND d.max) AND d.type='I' and d.gender=gender.meta_value)
	set wp_bhaa_leaguesummary.leaguedivision=d.code
	where league=2659;

update wp_bhaa_leaguesummary
join wp_bhaa_leaguerunnerdata on (wp_bhaa_leaguerunnerdata.league=wp_bhaa_leaguesummary.league and wp_bhaa_leaguerunnerdata.runner=wp_bhaa_leaguesummary.leagueparticipant)
set 
wp_bhaa_leaguesummary.leaguestandard=wp_bhaa_leaguerunnerdata.standard,
wp_bhaa_leaguesummary.leaguescorecount=wp_bhaa_leaguerunnerdata.racesComplete,
wp_bhaa_leaguesummary.leaguepoints=ROUND(wp_bhaa_leaguerunnerdata.pointsTotal,1);

-- update division
update wp_bhaa_leaguesummary 
JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant AND gender.meta_key = 'bhaa_runner_gender')
JOIN wp_bhaa_division d ON ((wp_bhaa_leaguesummary.leaguestandard BETWEEN d.min AND d.max) AND d.type='I' and d.gender=gender.meta_value)
set wp_bhaa_leaguesummary.leaguedivision=d.code and league=2492;
 
select d.code,wp_bhaa_leaguesummary.* from wp_bhaa_leaguesummary
JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant AND gender.meta_key = 'bhaa_runner_gender')
JOIN wp_bhaa_division d ON ((wp_bhaa_leaguesummary.leaguestandard BETWEEN d.min AND d.max) AND d.type='I' and d.gender=gender.meta_value)

-- update position in division
select * from wp_bhaa_leaguesummary
where wp_bhaa_leaguesummary.leaguedivision="A"
order by leaguepoints desc

-- http://stackoverflow.com/questions/3196971/mysql-update-statement-to-store-ranking-positions
SET @r=0;
SELECT *, @r:= (@r+1) as Ranking FROM wp_bhaa_leaguesummary
where wp_bhaa_leaguesummary.leaguedivision="A"
ORDER BY leaguepoints DESC;

SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="A" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="B" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="C" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="D" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="E" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="F" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="L1" ORDER BY leaguepoints DESC;
SET @r=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@r:= (@r+1)) where wp_bhaa_leaguesummary.leaguedivision="L2" ORDER BY leaguepoints DESC;

-- top ten per division
SELECT *,wp_users.display_name
FROM wp_bhaa_leaguesummary
left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
WHERE leaguetype = "I"
AND leagueposition <= 10 
AND league = 2659
order by league, leaguedivision desc, leaguepoints desc

SELECT leaguedivision, leagueposition,wp_users.display_name,wp_users.user_email,
mobile.meta_value as mobile,
(select post_title from wp_posts where id=company.meta_value) as company
FROM wp_bhaa_leaguesummary
left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
left JOIN wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')
left JOIN wp_usermeta mobile ON (mobile.user_id=wp_users.id AND mobile.meta_key = 'bhaa_runner_mobilephone')
WHERE leaguetype = "I"
AND leagueposition <= 10 
AND league = 2659
order by league, leaguedivision asc, leaguepoints desc

--brian maher ran 4miles in 19.06, i was checking the sp since the table sats std 30
-- i check the stanard against 6.4km and it's 1
select getStandard('00:19:06',6.4);
-- the lm race distance is 6.437376
select getRaceDistanceKm(2596);
-- put this value the sp and it gives 30
select getStandard('00:19:06',6.411);
-- something to do with rounding?


SELECT
  le.id,
  rr.runner,
  COUNT(rr.race) as racesComplete,
  getLeaguePointsTotal(le.id, rr.runner) as pointsTotal,
  AVG(rr.position) as averageOverallPosition,
  ROUND(AVG(rr.standard),0) as standard
  FROM wp_bhaa_raceresult rr
  inner join wp_posts r ON rr.race = r.id
  inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
  inner join wp_posts e ON e2r.p2p_from = e.id
  inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
  inner JOIN wp_posts le ON l2e.p2p_from = le.id
  inner JOIN wp_users ru ON rr.runner = ru.id
  JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key = 'bhaa_runner_status')
  JOIN wp_usermeta standard ON (standard.user_id=rr.runner AND standard.meta_key = 'bhaa_runner_standard')
  WHERE le.id=2492 AND class='RAN' AND standard.meta_value IS NOT NULL AND status.meta_value='M' and rr.runner=7713
  