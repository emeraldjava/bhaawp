
DROP TABLE IF EXISTS wp_bhaa_raceresult;
CREATE TABLE IF NOT EXISTS wp_raceresult (
	id int(11) NOT NULL AUTO_INCREMENT,
	race int(11) NOT NULL,
	runner int(11) NOT NULL,
	racetime time,
	position int(11),
	racenumber int(11),
	category varchar(6),
	standard int(11),
	actualstandard int(11),
	poststandard int(11),
	pace time DEFAULT NULL,
	posincat int(11) DEFAULT NULL,
	posinstd int(11) DEFAULT NULL,
	standardscoringset int(11) DEFAULT NULL,
	posinsss int(11) DEFAULT NULL,
	leaguepoints double DEFAULT NULL,
	class varchar(10),
	company int(11),
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE wp_bhaa_raceresult CHANGE COLUMN paceKM pace time;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN posincat int(11) DEFAULT NULL AFTER pace;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN posinstd int(11) DEFAULT NULL AFTER posincat;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN actualstandard int(11) DEFAULT NULL AFTER standard;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN poststandard int(11) DEFAULT NULL AFTER actualstandard;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN standardscoringset int(11) DEFAULT NULL AFTER poststandard;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN leaguepoints int(11) DEFAULT NULL AFTER posinstd;

-- group the league scoring set, position and points columns
ALTER TABLE wp_bhaa_raceresult MODIFY COLUMN standardscoringset int(11) DEFAULT NULL AFTER posinstd;
ALTER TABLE wp_bhaa_raceresult ADD COLUMN posinsss int(11) DEFAULT NULL AFTER standardscoringset;
ALTER TABLE wp_bhaa_raceresult MODIFY COLUMN leaguepoints double;

update wp_bhaa_raceresult set standard=NULL WHERE standard=0
update wp_bhaa_raceresult set posinsss=null,leaguepoints=null,standardscoringset=null
where race >=1783 AND runner =7713

select id,race,runner,standard,standardscoringset,gender.meta_value,posinsss,leaguepoints from wp_bhaa_raceresult 
join wp_usermeta gender ON (gender.user_id=wp_bhaa_raceresult.runner AND gender.meta_key = 'bhaa_runner_gender')

and standard IS NOT NULL
where runner=7713
where race=1786
where race=2499 and standard IS NOT NULL

--1786,1787,1785,1783,1784,2358,2359,2360,2362,2505,2504
--il
call updateRaceScoringSets(1786);
call updateRaceLeaguePoints(1786);
--vf
call updateRaceScoringSets(1787);
call updateRaceLeaguePoints(1787);
--boi
call updateRaceScoringSets(1785);
call updateRaceLeaguePoints(1785);
--teachers
call updateRaceScoringSets(1783);
call updateRaceLeaguePoints(1783);
call updateRaceScoringSets(1784);
call updateRaceLeaguePoints(1784);
--sdcc
call updateRaceScoringSets(2358);
call updateRaceLeaguePoints(2358);
call updateRaceScoringSets(2359);
call updateRaceLeaguePoints(2359);
-- eircom
call updateRaceScoringSets(2360);
call updateRaceLeaguePoints(2360);
call updateRaceScoringSets(2362);
call updateRaceLeaguePoints(2362);
-- garda
call updateRaceScoringSets(2499);
call updateRaceLeaguePoints(2499);
call updateRaceScoringSets(2500);
call updateRaceLeaguePoints(2500);
--airport
call updateRaceScoringSets(2505);
call updateRaceLeaguePoints(2505);
call updateRaceScoringSets(2504);
call updateRaceLeaguePoints(2504);
--aib
call updateRaceScoringSets(2532);
call updateRaceLeaguePoints(2532);
call updateRaceScoringSets(2531);
call updateRaceLeaguePoints(2531);
--ncf
call updateRaceScoringSets(2549);
call updateRaceLeaguePoints(2549);

select * from wp_bhaa_raceresult where race=2504;
update wp_bhaa_raceresult set pace=NULL,posincat=NULL,posinstd=NULL where race=2504;
call updatePositionInStandard(2504);

-- agecategory
select distinct(category) from wp_bhaa_raceresult
update wp_bhaa_raceresult set category='Senior' where category in ('SM','SW'); 
update wp_bhaa_raceresult set category=SUBSTRING(category,2,2) where SUBSTRING(category,1,1) in ('M','W');
update wp_bhaa_raceresult set category='Junior' where category in ('JM','JW'); 
select category,SUBSTRING(category,2,2),SUBSTRING(category,1,1) from wp_bhaa_raceresult where SUBSTRING(category,1,1) in ('M','W');

-- agecategory
update wp_bhaa_raceresult set actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=2504;


update wp_bhaa_raceresult 
join bhaaie_members.agecategory
set leaguepoints=getStandard(racetime,getRaceDistanceKm(race)) where race=2504;

-- league points
select * from bhaaie_members.racepoints where runner=7713
-- pointsbyscoringset
select wp_bhaa_raceresult.runner,wp_bhaa_raceresult.race,leaguepoints,tag,wp_bhaa_import.old,pointsbyscoringset from wp_bhaa_raceresult 
join wp_bhaa_import on (wp_bhaa_import.new=wp_bhaa_raceresult.race and type='race')
join bhaaie_members.racepoints on (bhaaie_members.racepoints.runner=wp_bhaa_raceresult.runner and bhaaie_members.racepoints.race=wp_bhaa_import.old)
where wp_bhaa_raceresult.runner=7713;

update wp_bhaa_raceresult set leaguepoints=10;

select * from wp_bhaa_raceresult 
where race in (2358,2359,2360,2362)
and runner=7713

-- select the matching league points
select rr.race,rr.leaguepoints,mrr.points from wp_bhaa_raceresult rr
join wp_bhaa_import i on (i.type='race' and i.new=rr.race)
join bhaaie_members.raceresult mrr on (mrr.race=i.old and mrr.runner=rr.runner)
and rr.race>=1783 
and rr.runner=7713;

update wp_bhaa_raceresult rr
join wp_bhaa_import i on (i.type='race' and i.new=rr.race)
join bhaaie_members.raceresult mrr on (mrr.race=i.old and mrr.runner=rr.runner)
set rr.leaguepoints=mrr.points
where rr.race>=1783 
and rr.runner=7713;

select 
id,
team,
league,
(select new from wp_bhaa_import where type='race' and old=race) as race,
standardtotal,
positiontotal,
class,
leaguepoints,
status
from bhaaie_members.teamraceresult where race=201282

-- select all the runners race league points!
select l.ID as lid,l.post_title,
e.ID as eid,e.post_title as etitle,eme.event_start_date as edate,
r.ID as rid,r.post_title as rtitle,r_type.meta_value as rtype,
rr.leaguepoints
from wp_posts l
inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
inner join wp_posts e on (e.id=l2e.p2p_to)
inner join wp_em_events eme on (eme.post_id=e.id)
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
inner join wp_posts r on (r.id=e2r.p2p_to)
inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
left join wp_bhaa_raceresult rr on (rr.race=r.id and rr.runner=7713)
where l.post_type='league'
and l.ID=2492;

-- select all race results linked to an event
select wp_bhaa_raceresult.* from wp_p2p e2r
left join wp_bhaa_raceresult on wp_bhaa_raceresult.race=e2r.p2p_from
where e2r.p2p_type='event_to_race' and e2r.p2p_from=2278


