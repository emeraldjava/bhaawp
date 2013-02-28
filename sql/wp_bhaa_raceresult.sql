
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

select * from wp_bhaa_raceresult where race=2504
call updatePositionInStandard(2504);