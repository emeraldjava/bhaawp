

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

delete from wp_bhaa_raceresult where race=2855 and runner=6933;

call updatePositions(2855);

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

-- ladies h A
insert into wp_bhaa_teamresult(class,team,company,runner,pos,std,racetime,id,race,position,totalpos,totalstd)
select 'W',52,52,rr.runner,rr.position,rr.standard,rr.racetime,null,2855,1,1,1 from wp_bhaa_raceresult rr
join wp_users u on (u.id=rr.runner)
join wp_p2p r2c ON (r2c.p2p_to=u.id AND r2c.p2p_type = 'sectorteam_to_runner')
where race=2855 and r2c.p2p_from=52

update wp_bhaa_teamresult set
-- sum postions and standards
select SUM(pos) as totalpos,SUM(std) as totalstd from wp_bhaa_teamresult
where race=2855 group by team,race

select * from wp_bhaa_teamresult where race=2855
delete from wp_bhaa_teamresult where race=2855
