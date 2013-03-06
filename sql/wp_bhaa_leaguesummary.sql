
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
and l.ID=2492;
and r_type.meta_value in ('C','S','*')

--inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
select runner,race,leaguepoints,e.ID as eid,e.post_title as etitle from wp_bhaa_raceresult 
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=race)
inner join wp_posts e on (e.id=e2r.p2p_from)
where race in (2358,2359,2360,2362)
and runner=7713;

update wp_bhaa_raceresult set leaguepoints=10;

SELECT   wp_posts.*, wp_p2p.* FROM wp_posts  
INNER JOIN wp_p2p WHERE 1=1  AND wp_posts.post_type IN ('league') 
AND (wp_posts.post_status = 'publish') AND (wp_p2p.p2p_type = 'league_to_event' 
AND wp_posts.ID = wp_p2p.p2p_from AND wp_p2p.p2p_to 
IN (SELECT   wp_posts.ID FROM wp_posts  WHERE 1=1  AND wp_posts.ID IN (0) 
AND wp_posts.post_type IN ('event') AND (wp_posts.post_status = 'publish')  
ORDER BY wp_posts.post_date DESC ))  ORDER BY wp_posts.post_date DESC



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




