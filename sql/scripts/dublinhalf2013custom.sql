

update dublinhalf2013entry set name=CONCAT(FirstName,' ',Surname)

select * from dublinhalf2013 where position=13;

select * from dublinhalf2013entry where BHAA=7713;

INSERT INTO `wp_bhaa_raceresult`(`race`, `runner`, `racetime`, `position`, `racenumber`,`class`)
select 2855,e.BHAA,r.time,r.position,r.racenumber,'RAN' from dublinhalf2013entry e
join dublinhalf2013 r on (r.name=e.name)

select rr.* from wp_bhaa_raceresult rr where race=2855;
delete from wp_bhaa_raceresult where runner=7713 and racenumber

select status.meta_value,rr.* from wp_bhaa_raceresult rr 
join wp_users on rr.runner=wp_users.id
join wp_usermeta standard on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_standard')
where race=2855;

select * from wp_bhaa_raceresult where race=2855;
delete from wp_bhaa_raceresult where id=32833;

select * from wp_bhaa_raceresult where race=2855 and runner=6035;
select runner,COUNT(runner) from wp_bhaa_raceresult where race=2855 group by runner;
getRunnerLeagueSummary

-- update the standard
update wp_bhaa_raceresult rr
join wp_users on rr.runner=wp_users.id
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_standard')
set rr.standard=status.meta_value
where rr.race=2855 and status.meta_value!='';

-- company runners
select meta.meta_value,rr.* from wp_bhaa_raceresult rr 
join wp_users on rr.runner=wp_users.id
left join wp_usermeta meta on (meta.user_id=wp_users.id and meta.meta_key='bhaa_runner_company')
where race=2855 and meta.meta_value!='';

-- company runners
select meta.meta_value,rr.* from wp_bhaa_raceresult rr 
join wp_users on rr.runner=wp_users.id
left join wp_usermeta meta on (meta.user_id=wp_users.id and meta.meta_key='bhaa_runner_company')
where race=2855 and meta.meta_value!='';

INSERT INTO `wp_bhaa_raceresult`(`id`, `race`, `runner`, `racetime`, `position`, `racenumber`,`class`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16],[value-17],[value-18])


select * from wp_bhaa_raceresult where runner=7713;
select * from wp_bhaa_raceresult where runner=7016;

select * from dublinhalf2013entry 
where BHAA NOT IN (select runner from wp_bhaa_raceresult where race=2855);

select * from dublinhalf2013 where name like '%Sinclair%';

INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,7373,'02:09:47','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,6446,'02:19:47','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,6448,'02:19:44','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,6350,'01:39:47','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,1549,'01:29:47','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,1627,'01:29:47','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,1515,'02:21:26','RAN');
INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,7016,'02:44:11','RAN');

INSERT INTO wp_bhaa_raceresult(race,runner,racetime,class) VALUES (2855,6933,'02:51:44','RAN');

INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (1300,2855,5101,'01:44:53','RAN');
INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (1300,2855,8732,'01:48.:9','RAN');

INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (4693,2855,4693,'01:20:05','RAN');
INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (5361,2855,5361,'01:33:42','RAN');
INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (6750,2855,6750,'01:20:46','RAN');
INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (7602,2855,7602,'02:21:36','RAN');
INSERT INTO wp_bhaa_raceresult(position,race,runner,racetime,class) VALUES (7595,2855,7595,'01:52:36','RAN');

call updatePositions(2855);
call updateRaceScoringSets(2855);
call updateRaceLeaguePoints(2855);
call updateLeagueData(2659);

delete from wp_bhaa_raceresult where race=2855 and runner=6933;


-- teams
select * from wp_bhaa_raceresult rr
join wp_users u on u.id=rr.runner
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=121

select * from wp_bhaa_raceresult rr
join wp_users u on u.id=rr.runner
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'sectorteam_to_runner')
where race=2855 and r2c.p2p_from=52

select * from wp_p2p where p2p_from=52
select * from wp_posts where ID=52

delete from wp_bhaa_teamresult where race=2855;
-- ladies h A
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'W',1,6,52,52,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'sectorteam_to_runner')
where race=2855 and r2c.p2p_from=52;

-- fire men A
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',7,1,159,159,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=159 and position<75 order by position;

insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'C',2,5,159,159,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=159 and position>75 order by position;

-- garda
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',8,1,94,94,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=94 and position<130 order by position;

-- RTE A
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',1,6,121,121,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=121 and position<50 order by position;

insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'B',3,4,121,121,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=121 and position>50 order by position;

-- DCC
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',4,3,93,93,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=93 and position<50 order by position;

-- revenue A
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',5,2,137,137,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=137 and position<70 order by position;

-- revenue B
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'C',1,6,137,137,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=137 and position>70 and position<120 order by position;

-- esb
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'B',1,6,97,97,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=97 and position<70 order by position;

-- eircom
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',6,1,110,110,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=110 and position<120 order by position;

-- boi
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',2,5,161,161,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=161 and position>1 order by position;

-- swords labs
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'A',3,4,204,204,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=204 and position>1 order by position;

-- zurich
insert into wp_bhaa_teamresult(class,position,leaguepoints,team,company,runner,pos,std,racetime,id,race,totalpos,totalstd)
select 'B',2,5,175,175,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'house_to_runner')
where race=2855 and r2c.p2p_from=175 and position>1 order by position;

update wp_bhaa_raceresult set standard=13 where race=2855 and runner=7373;
update wp_bhaa_raceresult set standard=10 where race=2855 and runner=1704;
-- update sums
UPDATE wp_bhaa_teamresult tr
JOIN ( 
select race,class,team,SUM(pos) as totalpos,SUM(std) as totalstd from wp_bhaa_teamresult
where race=2855 group by race,team,class
) i
ON tr.race=i.race and tr.team=i.team and tr.class=i.class
SET tr.totalpos=i.totalpos,tr.totalstd=i.totalstd;
-- update names
UPDATE wp_bhaa_teamresult tr
JOIN wp_posts h on (tr.team=h.id and h.post_type='house')
set tr.teamname=h.post_title,tr.companyname=h.post_title
where race=2855;

select * from wp_bhaa_teamresult where race=2855 order by class,totalpos;
delete from wp_bhaa_teamresult where race=2855;


-- sum postions and standards
select team,SUM(pos) as totalpos,SUM(std) as totalstd from wp_bhaa_teamresult
where race=2855 group by team,race

select MIN(totalstd),MAX(totalstd) from wp_bhaa_teamresult where class = "D" ('A','B','C','D')
select * from wp_bhaa_division
select from wp_meta where user

select wp_bhaa_teamresult.*,wp_users.display_name from wp_bhaa_teamresult
join wp_users on wp_users.id=wp_bhaa_teamresult.runner
where race=2855 order by class,position,team

call getRunnerLeagueSummary(5101,2812,'M');
select getRunnerLeagueSummary(6553,2812,'M');

update wp_bhaa_leaguesummary set leaguesummary=getRunnerLeagueSummary(leagueparticipant,2812,'M') where league=2812 and leaguedivision in ('D');

select * from wp_bhaa_raceresult where race>=2596 and race<=2855 and class='PRE_REG'
delete from wp_bhaa_raceresult where race>=2596 and race<=2855 and class='PRE_REG'

select * from wp_bhaa_raceresult where runner=6553 and race>=2596 and race<=2855
