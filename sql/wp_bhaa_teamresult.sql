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
where race=2598
and class="A" order by position asc

select * from wp_bhaa_teamresult where team=0;
delete from wp_bhaa_teamresult where team=0;

-- update the team league points
update wp_bhaa_teamresult set leaguepoints=(7-(position))
update wp_bhaa_teamresult set leaguepoints=1 where leaguepoints<=0

-- 2811
SELECT
t1.league,
t1.leaguetype,
t1.leagueparticipant,
t1.leaguestandard as leaguestandard,
'W' AS leaguedivision,
@rownum:=@rownum+1 AS leagueposition,
t1.previousleagueposition,
t1.leaguescorecount,
t1.leaguepoints - (SELECT count(1) FROM wp_bhaa_teamresult where team = t1.leagueparticipant and class='OW') as leaguepoints,
t1.leaguesummary AS leaguesummary
FROM
(
SELECT
2811 AS league,
'T' AS leaguetype,
l.team AS leagueparticipant,
0 AS leaguestandard,
0 AS leaguedivision,
0 AS previousleagueposition,
SUM(l.leaguescorecount) AS leaguescorecount,
SUM(l.leaguepoints) AS leaguepoints,
GROUP_CONCAT( cast( concat(l.event,':',l.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
FROM
(
SELECT 1 AS leaguescorecount, team, race, MAX(leaguepoints) AS leaguepoints, e2r.p2p_from as event
FROM wp_bhaa_teamresult trr
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=trr.race)
WHERE class  in ('W','OW')
GROUP BY team,race
) l
GROUP BY l.team
ORDER BY leaguepoints DESC
)t1, (SELECT @rownum:=0) t2;

-- mens team league
SELECT
t1.league,
t1.leaguetype,
t1.leagueparticipant,
t1.leaguestandard as leaguestandard,
'M' AS leaguedivision,
@rownum:=@rownum+1 AS leagueposition,
t1.leaguescorecount,
t1.leaguepoints - (SELECT count(1) FROM wp_bhaa_teamresult where team = t1.leagueparticipant and class='O') as leaguepoints,
t1.leaguesummary AS leaguesummary
FROM
(
SELECT
2811 AS league,
'T' AS leaguetype,
l.team AS leagueparticipant,
0 AS leaguestandard,
0 AS leaguedivision,
SUM(l.leaguescorecount) AS leaguescorecount,
SUM(l.leaguepoints) AS leaguepoints,
GROUP_CONCAT( cast( concat(l.event,':',l.leaguepoints) AS char ) SEPARATOR ',') AS leaguesummary
FROM
(
SELECT 1 AS leaguescorecount, team, race, MAX(leaguepoints) AS leaguepoints, e2r.p2p_from as event
FROM wp_bhaa_teamresult trr
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=race)
WHERE class <> 'W' and class <> 'OW'
GROUP BY team,race
) l
GROUP BY l.team
ORDER BY leaguepoints DESC
)t1, (SELECT @rownum:=0) t2;


-- migrate old team results
select * from wp_bhaa_teamresult where race=2596
select * from teamraceresult where race=201073

-- http://stackoverflow.com/questions/13944417/mysql-convert-column-to-row-pivot-table
select
1 as id,
race,
class,
leaguepoints as position,
team,
"teamname" as teamname,
positiontotal as totalpos,
standardtotal as totalstd,
runnerfirst as runner,
leaguepoints
from teamraceresult where race=201073
UNION
select 
2 as id,
race,
class,
leaguepoints as position,
team,
"teamname" as teamname,
positiontotal as totalpos,
standardtotal as totalstd,
runnersecond as runner,
leaguepoints
from teamraceresult where race=201073
UNION
select 
3 as id,
race,
class,
leaguepoints as position,
team,
"teamname" as teamname,
positiontotal as totalpos,
standardtotal as totalstd,
runnerthird as runner,
leaguepoints
from teamraceresult
where race=201073
order by class, leaguepoints desc, id

select the appropriate columns and then each runner in 3 unioned queries
select x,y, runner first as runner
union
select x,y, runnersecond as runner
union
select x,y, runnerthird as runner
