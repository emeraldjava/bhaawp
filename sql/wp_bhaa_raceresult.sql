
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
	standardscoringset int(11),
	pace time,
	posincat int(11) DEFAULT NULL,
	posinstd int(11) DEFAULT NULL,
	leaguepoints int(11) DEFAULT NULL,
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

