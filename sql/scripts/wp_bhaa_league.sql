-- schema setup
DROP TABLE wp_bhaa_teamresult;
DROP TABLE wp_bhaa_leaguesummary;
DROP TABLE wp_bhaa_race_detail;
DROP TABLE p2p;

-- http://sqlfiddle.com/#!2/87bf94
CREATE TABLE wp_bhaa_teamresult (
	id int(11) auto_increment primary key,
	race int(11) NOT NULL,
	class varchar(1) NOT NULL,
	raceteam int(11) NOT NULL,
	position int(11) NOT NULL,
	team int(11) NOT NULL,
	teamname varchar(20),
	leaguepoints double,
	runner int(11) NOT NULL
);

-- simulate DCC, Kclub, RTE races
-- I've added 'raceteam' value to the table, which makes it easier to pick a multi garda team from a specific race
-- We should only record 'leaguepoints' against the best scoring team
-- The team league summing logic can then exclude teams with league points of 0.
INSERT INTO wp_bhaa_teamresult
(race,class,raceteam,position,team,teamname,leaguepoints,runner)
VALUES
(1,'A',1,1,97,'Garda',6,1),
(1,'A',1,1,97,'Garda',6,2),
(1,'A',1,1,97,'Garda',6,3),
(1,'A',2,2,91,'ESB',5,1),
(1,'A',2,2,91,'ESB',5,2),
(1,'A',2,2,91,'ESB',5,3),
(1,'A',3,3,90,'RTE',4,1),
(1,'A',3,3,90,'RTE',4,2),
(1,'A',3,3,90,'RTE',4,3),
(1,'B',4,1,1,'1',6,1),
(1,'B',4,1,1,'1',6,2),
(1,'B',4,1,1,'1',6,3),
(1,'B',5,2,2,'2',5,1),
(1,'B',5,2,2,'2',5,2),
(1,'B',5,2,2,'2',5,3),
(1,'B',7,3,90,'RTE',0,1), --second RTE team with the same position but pick the higher class
(1,'B',7,3,90,'RTE',0,2),
(1,'B',7,3,90,'RTE',0,3),
(1,'B',6,3,97,'Garda',0,1),
(1,'B',6,3,97,'Garda',0,2),
(1,'B',6,3,97,'Garda',0,3),
(4,'W',1,1,10,'Women',6,1),
(4,'W',1,1,10,'Women',6,2),
(4,'W',1,1,10,'Women',6,3),
(2,'A',1,1,1,'1',6,1),
(2,'A',1,1,1,'1',6,2),
(2,'A',1,1,1,'1',6,3),
(2,'A',2,2,91,'ESB',5,1),
(2,'A',2,2,91,'ESB',5,2),
(2,'A',2,2,91,'ESB',5,3),
(2,'A',3,3,3,'3',4,1),
(2,'A',3,3,3,'3',4,2),
(2,'A',3,3,3,'3',4,3),
(2,'C',4,1,4,'4',6,1),
(2,'C',4,1,4,'4',6,2),
(2,'C',4,1,4,'4',6,3),
(2,'W',5,1,10,'Women',6,1),
(2,'W',5,1,10,'Women',6,2),
(2,'W',5,1,10,'Women',6,3),
(3,'A',1,1,90,'RTE',6,1),
(3,'A',1,1,90,'RTE',6,2),
(3,'A',1,1,90,'RTE',6,3),
(3,'A',2,2,91,'ESB',5,1),
(3,'A',2,2,91,'ESB',5,2),
(3,'A',2,2,91,'ESB',5,3),
(3,'A',3,3,97,'Garda',4,1),
(3,'A',3,3,97,'Garda',4,2),
(3,'A',3,3,97,'Garda',4,3),
(3,'B',4,1,3,'3',6,1),
(3,'B',4,1,3,'3',6,2),
(3,'B',4,1,3,'3',6,3);

-- this really is the p2p table with type=league_to_event
--  wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
CREATE TABLE p2p (
	id int(11) auto_increment primary key,
	league int(11) NOT NULL,
	event int(11) NOT NULL,
	type varchar(20) NOT NUll
);
-- 1 is the team league (no trinity)
-- 2 is the individual league
INSERT INTO p2p
(league,event,type)
VALUES
(1,1,"league_to_event"),
(1,2,"league_to_event"),
(1,3,"league_to_event"),
(2,1,"league_to_event"),
(2,2,"league_to_event"),
(2,3,"league_to_event"),
(2,4,"league_to_event");

-- show the 5 race types with track and summary
CREATE TABLE wp_bhaa_race_detail (
	id int(11) auto_increment primary key,
	event int(11) NOT NULL,
	eventname varchar(10) NOT NULL,
	race int(11) NOT NULL,
	type varchar(1) NOT NULL,
	distance double
);

INSERT INTO wp_bhaa_race_detail
(event,eventname,race,type,distance)
VALUES
(1,"DCC",4,"W",2),
(1,"DCC",1,"M",4),
(2,"RTE",2,"C",5),
(3,"KCLUB",3,"C",10)
(4,"TRINITY",5,"TRACK",1)
(4,"TRINITY",6,"S",1);-- used for individ league scoring

CREATE TABLE wp_bhaa_leaguesummary (
	league int(10) NOT NULL,
	leaguetype enum('I','T') NOT NULL,
	leagueparticipant int(10) NOT NULL,
	leaguestandard int(10) NOT NULL,
	leaguedivision varchar(5) NOT NULL,
	leagueposition int(10) NOT NULL,
	leaguescorecount int(10) NOT NULL,
	leaguepoints double NOT NULL,
	leaguesummary varchar(500)
);


-- SQL Queries
select * from wp_bhaa_teamresult where race=1;

-- order the best scroing teams for a specific race
select race,teamname,position,MAX(leaguepoints),class from wp_bhaa_teamresult 
where race=1
group by race,team
order by class,position

-- order the best scroing teams for a specific race
select race,teamname,team,position,MAX(leaguepoints),class from wp_bhaa_teamresult 
group by race,team
order by race,class,position

-- get the league totals
select teamname,team,ROUND(COUNT(race)/3) as ran,SUM(leaguepoints)/3 as total
from wp_bhaa_teamresult 
where leaguepoints!=0
group by team
order by total desc

-- add the summary of race results
select teamname,team,ROUND(COUNT(race)/3) as ran,SUM(leaguepoints)/3 as total,
(select GROUP_CONCAT( cast( concat('[r=',race,':p=',leaguepoints,']') AS char ) SEPARATOR ',') ) AS summary
from wp_bhaa_teamresult 
where leaguepoints!=0
group by team
order by total desc





select l.ID as lid,l.post_title,
e.ID as eid,e.post_title as etitle,eme.event_start_date as edate,
r.ID as rid,r.post_title as rtitle,r_type.meta_value as rtype,
IFNULL(rr.leaguepoints,0) as points
from wp_posts l
join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
join wp_posts e on (e.id=l2e.p2p_to)
join wp_em_events eme on (eme.post_id=e.id)
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
join wp_posts r on (r.id=e2r.p2p_to)
join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
left join wp_bhaa_raceresult rr on (r.id=rr.race and rr.runner=7713)
where l.post_type='league'
and l.ID=2659 and r_type.meta_value in ('C','S','W','M') order by eme.event_start_date ASC

select GROUP_CONCAT(CAST(concat('[r=',rr.race,':p=',IFNULL(rr.leaguepoints,0),']') AS CHAR) ORDER BY eme.event_start_date SEPARATOR ',') 
from wp_posts p
left join wp_bhaa_raceresult rr on (p.id=rr.race and rr.runner=6762)
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=p.id)
join wp_em_events eme on (eme.post_id=e2r.p2p_from)
where p.ID IN (
select r.ID from wp_posts l
inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_from=l.ID)
inner join wp_posts e on (e.id=l2e.p2p_to)
inner join wp_em_events eme on (eme.post_id=e.id)
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=e.ID)
inner join wp_posts r on (r.id=e2r.p2p_to)
inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
where l.post_type='league'
and l.ID=2659 and r_type.meta_value in ('C','S','M') AND r_type.meta_value!='TRACK' order by eme.event_start_date ASC
) order by eme.event_start_date ASC

