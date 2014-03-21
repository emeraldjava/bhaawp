
-- wp_bhaa_race_detail table
DROP TABLE wp_bhaa_race_detail;
CREATE TABLE wp_bhaa_race_detail (
	id int(11) auto_increment primary key,
	league int(11) NULL,
	leaguetype varchar(1) NULL,
	event int(11) NULL,
	eventname varchar(40) NULL,
	eventdate date NULL,
	race int(11) NULL,
	racetype varchar(1) NULL,
	distance varchar(4),
	unit varchar(4) NULL
);

SELECT * FROM wp_bhaa_race_detail;
DELETE FROM wp_bhaa_race_detail;

INSERT INTO wp_bhaa_race_detail (league,leaguetype,event,eventname,eventdate,race,racetype,distance,unit)
select 
l2e.p2p_from as league,
leaguetype.meta_value as leaguetype,
event.ID as event,
event.post_title as eventname,
event.post_date as eventdate,
race.ID as race,
racetype.meta_value as racetype,
racedistance.meta_value as distance,
raceunit.meta_value as raceunit
from wp_p2p l2e
join wp_posts event on (l2e.p2p_to=event.ID)
join wp_p2p e2r on (l2e.p2p_to=e2r.p2p_from AND e2r.p2p_type='event_to_race')
join wp_posts race on (e2r.p2p_to=race.ID)
LEFT join wp_postmeta racetype on (race.ID=racetype.post_id AND racetype.meta_key='bhaa_race_type')
LEFT join wp_postmeta racedistance on (race.ID=racedistance.post_id AND racedistance.meta_key='bhaa_race_distance')
LEFT join wp_postmeta raceunit on (race.ID=raceunit.post_id AND raceunit.meta_key='bhaa_race_unit')
LEFT join wp_postmeta leaguetype on (l2e.p2p_from=leaguetype.post_id AND leaguetype.meta_key='bhaa_league_type')
where l2e.p2p_type='league_to_event' and l2e.p2p_from IN (3103,3615)
ORDER BY eventdate;

select DISTINCT(meta_key) from wp_postmeta;
select DISTINCT(post_type) from wp_posts;
select ID,post_title from wp_posts where post_type="league";

-- 3523 the garda teams from AIB/NUI
select * from wp_bhaa_teamresult 
WHERE race=3523 AND team=94
ORDER BY position,class

ALTER TABLE wp_bhaa_teamresult ADD COLUMN points DOUBLE DEFAULT NULL AFTER leaguepoints;

select race,team,teamname,MAX(leaguepoints),totalpos from wp_bhaa_teamresult 
WHERE race=3523 
AND team=94
GROUP BY race,team
-- ORDER BY leaguepoints desc, totalpos
limit 1;

-- http://stackoverflow.com/questions/19401155/mysql-update-max-value-with-group-by
-- http://stackoverflow.com/questions/16910050/get-row-with-highest-or-lowest-value-from-a-group-by
-- the best teams in race
select race,team,teamname,id,class,position,MAX(leaguepoints),totalpos from wp_bhaa_teamresult 
WHERE race=3523 
GROUP BY race,team
ORDER BY leaguepoints desc,totalpos

select * from wp_bhaa_teamresult r
inner join 

-- group by race and team position, order by league points
select race,team,teamname,class,leaguepoints,position,totalpos,points from wp_bhaa_teamresult 
WHERE race=3523 
GROUP BY race,totalpos
ORDER BY race,team,leaguepoints desc

UPDATE wp_bhaa_teamresult oteam,
(
select i.race,i.team,i.teamname,MAX(i.leaguepoints) as bestpoints,i.totalpos,COUNT(DISTINCT(i.totalpos)) as teams from wp_bhaa_teamresult i
WHERE i.race=race
AND i.team=team
GROUP BY i.race,i.team
) best
SET oteam.points=best.bestpoints
where oteam.team=best.team
AND oteam.totalpos=best.totalpos
AND oteam.race=3523;



