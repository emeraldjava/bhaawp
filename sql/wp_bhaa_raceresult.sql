
DROP TABLE IF EXISTS wp_bhaa_raceresult;
CREATE TABLE IF NOT EXISTS wp_bhaa_raceresult (
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

alter table wp_bhaa_raceresult add unique index(race, racenumber, class);
alter table wp_bhaa_raceresult add unique index(race, runner, class);
delete from wp_bhaa_raceresult where race=2596;
select * from wp_bhaa_raceresult where race=2596;

select COUNT(*) from wp_bhaa_raceresult where race=2596 and runner=7713
select COUNT(*) from wp_bhaa_raceresult where race=2596 and racenumber=7713

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
left join wp_bhaa_raceresult on (wp_bhaa_raceresult.race=e2r.p2p_to and e2r.p2p_type='event_to_race')
where e2r.p2p_from=2278

select * from wp_p2p where p2p_type='event_to_race' and p2p_from=2278

-- select racetec RACE_REG details
SELECT wp_bhaa_raceresult.id,runner,racenumber,race,
firstname.meta_value as firstname,lastname.meta_value as lastname,
gender.meta_value as gender,dateofbirth.meta_value as dateofbirth,
status.meta_value as status,standard,
house.id as company, 
CASE WHEN house.post_title IS NULL THEN companyname.post_title ELSE house.post_title END as companyname,
CASE WHEN sector.id IS NOT NULL THEN sector.id ELSE house.id END as teamid,
CASE WHEN sector.post_title IS NOT NULL THEN sector.post_title ELSE house.post_title END as teamname
from wp_bhaa_raceresult
JOIN wp_p2p e2r ON (wp_bhaa_raceresult.race=e2r.p2p_to AND e2r.p2p_type="event_to_race")
JOIN wp_users on (wp_users.id=wp_bhaa_raceresult.runner) 
left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = 'house_to_runner')
left join wp_posts house on (house.id=r2c.p2p_from and house.post_type='house')
left join wp_p2p r2s ON (r2s.p2p_to=wp_users.id AND r2s.p2p_type = 'sectorteam_to_runner')
left join wp_posts sector on (sector.id=r2s.p2p_from and house.post_type='house')
left join wp_usermeta firstname ON (firstname.user_id=wp_users.id AND firstname.meta_key = 'first_name')
left join wp_usermeta lastname ON (lastname.user_id=wp_users.id AND lastname.meta_key = 'last_name')
left join wp_usermeta gender ON (gender.user_id=wp_users.id AND gender.meta_key = 'bhaa_runner_gender')
left join wp_usermeta dateofbirth ON (dateofbirth.user_id=wp_users.id AND dateofbirth.meta_key = 'bhaa_runner_dateofbirth')
left join wp_usermeta status ON (status.user_id=wp_users.id AND status.meta_key = 'bhaa_runner_status')
left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')
left join wp_posts companyname on (companyname.id=company.meta_value and companyname.post_type='house')
where wp_bhaa_raceresult.class="PRE_REG" 
AND e2r.p2p_from=2282 order by wp_bhaa_raceresult.id desc limit 3

--left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')

left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')
join wp_posts house on (house.id=company.meta_value and house.post_type='house')
left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = 'house_to_runner')

-- actual standard and pace

select runner,position,racetime,
SEC_TO_TIME(TIME_TO_SEC(racetime)/5.2) as pace,
getStandard(racetime,getRaceDistanceKm(race)) as actualstandard
from wp_bhaa_raceresult
where race=2549

update wp_bhaa_raceresult set pace=SEC_TO_TIME(TIME_TO_SEC(racetime)/5.2),actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=2549


-- list all race results for a specific league
SELECT e.id,
  CASE rr.leaguepoints WHEN 11 THEN 10 ELSE rr.leaguepoints END AS points
  FROM wp_bhaa_raceresult rr
  join wp_posts r ON rr.race = r.id
  right join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
  join wp_posts e ON e2r.p2p_from = e.id
  right join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
  JOIN wp_posts le ON l2e.p2p_from = le.id
  WHERE runner=7713 AND le.id=2492

-- select a points results for all events regardless of a raceresult
select
e.ID as eid,e.post_title as etitle,eme.event_start_date as edate,
r.ID as rid,r.post_title as rtitle,r_type.meta_value as rtype,
CASE rr.leaguepoints WHEN 11 THEN 10 ELSE rr.leaguepoints END AS points,
rr.class,rr.standard
from wp_posts l
inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
inner join wp_posts e on (e.id=l2e.p2p_to)
inner join wp_em_events eme on (eme.post_id=e.id)
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
inner join wp_posts r on (r.id=e2r.p2p_to)
inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
left join wp_bhaa_raceresult rr on (rr.race=r.id and rr.runner=7713)
where l.post_type='league'
and l.ID=2492
and r_type.meta_value in ('C','M')

-- insert pre-register runners into the race
select * from wp_em_bookings where event_id=113

insert into wp_bhaa_raceresult(race,runner,class)
select 2598,person_id,'PRE_REG'
from wp_em_bookings 
join wp_users on wp_users.id=wp_em_bookings.person_id
where event_id=113
and booking_status=1
order by display_name desc

select 2598,person_id,display_name,'PRE_REG'
from wp_em_bookings
join wp_users on wp_users.id=wp_em_bookings.person_id
where event_id=113
order by display_name desc

select * from wp_bhaa_raceresult where class="RACE_REG" and race=2598
select * from wp_bhaa_raceresult where class="PRE_REG" and race=2598
delete from wp_bhaa_raceresult where class="PRE_REG" and race=2598

-- race entry stat's
select count(runner) as total,
(select count(runner) from wp_bhaa_raceresult where gender="M") as male,
(select count(runner) from wp_bhaa_raceresult where gender="W") as female
from wp_bhaa_raceresult
where race=2598

explain
select count(id) from wp_bhaa_raceresult where race=2598 and runner=7713
explain
select count(id) from wp_bhaa_raceresult where race=2598 and runner=7713 and class="RACE_REG"
explain
select exists(select * from wp_bhaa_raceresult where race=2598 and runner=7713);
select exists(select * from wp_bhaa_raceresult where race=2598 and runner=7713);

-- indexes and class alter
ALTER TABLE wp_bhaa_raceresult ADD INDEX index_race_runner (race,runner);
ALTER TABLE wp_bhaa_raceresult ADD INDEX index_race_number (race,racenumber);
ALTER TABLE wp_bhaa_raceresult ADD INDEX index_race_number_class (race,racenumber,class);
ALTER TABLE wp_bhaa_raceresult CHANGE class class VARCHAR(10) NOT NULL;

-- find raceresult without a runner
select * from wp_bhaa_raceresult
left join wp_users on wp_users.id=runner
where wp_users.id is null

-- find runners who ran with no standard
select * from wp_bhaa_raceresult
left join wp_users on wp_users.id=runner
left join wp_usermeta on (wp_users.id=wp_usermeta.user_id and wp_usermeta.meta_key='bhaa_runner_standard')
GROUP BY user_id
HAVING count(wp_usermeta.umeta_id) = 0;

-- give break down of registered runners
select standardscoringset as type, count(*) as count
from wp_bhaa_raceresult 
where race=2849
and class="RACE_REG"
group by standardscoringset;
