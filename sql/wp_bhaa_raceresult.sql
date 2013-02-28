
DROP TABLE IF EXISTS wp_bhaa_raceresult;
CREATE TABLE IF NOT EXISTS wp_raceresult (
	id int(11) NOT NULL AUTO_INCREMENT,
	race int(11) NOT NULL,
	runner int(11) NOT NULL,
	racetime time,
	position int(11),
	racenumber int(11),
	category varchar(5),
	standard int(11),
	actualstandard int(11),
	poststandard int(11),
	standardscoringset int(11),
	pace time,
	posincat int(11) DEFAULT NULL,
	posinstd int(11) DEFAULT NULL,
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

select * from wp_bhaa_raceresult where race=2504;
update wp_bhaa_raceresult set pace=NULL,posincat=NULL,posinstd=NULL where race=2504;
call updatePositionInStandard(2504);

-- agecategory
select distinct(category) from wp_bhaa_raceresult
update wp_bhaa_raceresult set category='Senior' where category in ('SM','SW'); 
update wp_bhaa_raceresult set category=SUBSTRING(category,1,2) where SUBSTRING(category,1,1) in ('M','W');
update wp_bhaa_raceresult set category='Junior' where category in ('JM','JW'); 

-- agecategory
update wp_bhaa_raceresult set actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=2504;

