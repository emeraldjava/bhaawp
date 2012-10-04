
select * from company where id=330;

select count(id),min(id),max(id) from wp_posts where post_type="company"
select count(id),min(id),max(id) from wp_posts where post_type="post"
select count(id),min(id),max(id) from wp_posts where post_type="page"
select count(id),min(id),max(id) from wp_posts where post_type="house"
select count(id),min(id),max(id) from wp_posts where post_type="event"
select count(id),min(id),max(id) from wp_posts where post_type="race"

select * from company where id=574
select * from company where id=97

select runner.id,runner.company,runner.companyname,team.id,team.name,team.type
from runner
join teammember on teammember.runner=runner.id
join team on team.id=teammember.team
where runner.status!="D" and runner.company!=0 order by runner.company

DELETE FROM `wp_p2p` WHERE `p2p_type`='house_to_runner';
DELETE FROM `wp_p2p` WHERE `p2p_type`='sectorteam_to_runner';
DELETE FROM `wp_p2p` WHERE `p2p_type`='company_to_runner';

select * from wp_users where id=1597;

select * from wp_p2pmeta
delete from wp_p2pmeta

select * from wp_posts where post_type="page";

select * from team;

select * from team where type="S" order by id asc;
select team.id,team.name,sector.name from team
join sector on sector.id=team.parent
where type="S" order by id asc;

select * from team where type="C" order by id asc;


select count(id),min(id),max(id) from team where type="C" order by id asc;
select count(id),min(id),max(id) from team where type="S" order by id asc;

select * from company
left join team on team.id=company.id
where team.type="S" order by company.id;

select id from team where type="S";

select * from teammember where runner=1506
delete from teammember where runner=1506 and team=84

select runner.id as runner,runner.company,runner.companyname,team.id as team,team.name as teamname,team.type
from runner
join teammember on teammember.runner=runner.id
join team on team.id=teammember.team
where runner.status!="D" and runner.company!=0 order by runner.id limit 50

desc wp_bhaa_raceresult
desc raceresult
select * from wp_bhaa_raceresult
delete from wp_bhaa_raceresult

insert into wp_bhaa_raceresult(id,race,runner,racetime,position,racenumber,standard,company)
select 0,race,runner,racetime,position,racenumber,standard,company
from raceresult
where race=36
order by race,runner
