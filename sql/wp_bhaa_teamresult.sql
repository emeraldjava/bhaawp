-- wp_bhaa_teamresult

--Pos,RaceName,EventDescr,TeamType,Team Pos,TeamTypeId,TeamId,TempTeamId,Team Total,Team Name,Team Std,RaceNo,Name,Gender,Company,Overall Pos,Finish Time,Std,Class,Team No,Company No,RaceId,EventId
--1,BHAA NCF 5km Night XC 2013,5.2km,BHAA,1,1,26,2,17,Swords Labs,21,1782,Chris Muldoon,Male,Swords Labs,2,00:18:35,6,A,204,204,47,1
--4,BHAA NCF 5km Night XC 2013,5.2km,BHAA,8,1,1,2,160,RTE,39,1809,Terry Clarke,Male,Rte,33,00:22:07,11,B,121,121,47,1

DROP TABLE IF EXISTS wp_bhaa_teamresult;
CREATE TABLE IF NOT EXISTS wp_bhaa_teamresult (
	id int(11) NOT NULL AUTO_INCREMENT,
	race int(11) NOT NULL,
	class varchar(1) NOT NULL,
	position int(11) NOT NULL,
	team int(11) NOT NULL,
	teamname varchar(20),
	totalpos int(11) NOT NULL,
	totalstd int(11) NOT NULL,
	runner int(11) NOT NULL,
	pos int(11) NOT NULL,
	std int(11) NOT NULL,
	racetime time,
	company int(11),
	companyname varchar(20),
	leaguepoints double(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE wp_bhaa_teamresult MODIFY COLUMN leaguepoints double;

select * from wp_bhaa_teamresult 
select class, position, team, leaguepoints, (6.5-(position*.5)) from wp_bhaa_teamresult 
where race=2598
and class="A" order by position asc

update wp_bhaa_teamresult
set leaguepoints=(6.5-(position*.5))