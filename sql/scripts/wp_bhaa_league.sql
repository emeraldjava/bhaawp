-- schema setup
DROP TABLE wp_bhaa_teamresult;
DROP TABLE wp_bhaa_league;

CREATE TABLE wp_bhaa_teamresult (
	id int(11) auto_increment primary key,
	race int(11) NOT NULL,
	class varchar(1) NOT NULL,
	position int(11) NOT NULL,
	team int(11) NOT NULL,
	teamname varchar(20),
	leaguepoints double
);

INSERT INTO wp_bhaa_teamresult
(race,class,position,team,teamname,leaguepoints)
VALUES
(1,'A',1,97,'Garda',6),
(1,'A',2,91,'ESB',5),
(1,'A',3,90,'RTE',4),
(1,'B',1,1,'1',6),
(1,'B',2,2,'2',5),
(1,'B',3,97,'Garda',4),
(2,'A',1,1,'1',6),
(2,'A',2,91,'ESB',5),
(2,'A',3,3,'3',4),
(2,'C',1,4,'4',6),
(3,'A',1,90,'RTE',6),
(3,'A',2,91,'ESB',5),
(3,'A',3,97,'Garda',4),
(3,'B',1,3,'3',6)
;

CREATE TABLE wp_bhaa_league (
	id int(11) auto_increment primary key,
	league int(11) NOT NULL,
	event int(11) NOT NULL,
	eventname varchar(10) NOT NULL,
	race int(11) NOT NULL,
	type varchar(1) NOT NULL,
	distance double
);

INSERT INTO wp_bhaa_league
(league,event,eventname,race,type,distance)
VALUES
(1,1,"DCC",4,"W",2),
(1,1,"DCC",1,"M",4),
(1,2,"RTE",2,"C",5),
(1,3,"KCLUB",3,"C",10);

-- SQL Queries
select * from wp_bhaa_teamresult;
select * from wp_bhaa_league;
