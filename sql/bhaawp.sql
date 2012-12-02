
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

-- team result
desc wp_bhaa_teamresult
desc teamraceresult
insert into wp_bhaa_teamresult(id,team,league,race,standardtotal,positiontotal,class,leaguepoints,status)
select id,team,league,race,standardtotal,positiontotal,class,leaguepoints,status
from teamraceresult

-- race result query
SELECT wp_bhaa_raceresult.*,wp_users.display_name,wp_posts.id,wp_posts.post_title
FROM wp_bhaa_raceresult 
left join wp_users on wp_users.id=wp_bhaa_raceresult.runner 
left join wp_posts on wp_posts.post_type='house' and wp_bhaa_raceresult.company=wp_posts.id
where race=2 order by position

-- runner result query
SELECT wp_bhaa_raceresult.* FROM 
wp_bhaa_raceresult
join wp_users on wp_users.id=wp_bhaa_raceresult.runner
where runner=7713 order by race desc

left join wp_usermeta as cid on cid.user_id=wp_users.id and cid.meta_key="bhaa_runner_company"
left join wp_usermeta as cname on cname.user_id=wp_users.id and cname.meta_key="bhaa_runner_companyname"
-- insert users
insert into wp_users (ID,user_login,user_pass,display_name)
select id,(concat(firstname,'.',surname)),id,(concat(firstname,'.',surname)) from runner
where runner.status="D"

delete from wp_users where ID>10000

-- map the wp race to the bhaa race
select id,post_name,wp_postmeta.meta_value from wp_posts 
join wp_postmeta on (wp_postmeta.post_id=wp_posts.id and wp_postmeta.meta_key='bhaa_race_id')
where wp_posts.post_type='race';

create table race_mapping
(
	wp_race_id int,
	bhaa_race_id int
);
select * from race_mapping where bhaa_race_id=8;

insert into race_mapping (wp_race_id,bhaa_race_id)
select id,wp_postmeta.meta_value 
from wp_posts 
join wp_postmeta on (wp_postmeta.post_id=wp_posts.id and wp_postmeta.meta_key='bhaa_race_id')
where wp_posts.post_type='race';
select * from race_mapping;

update wp_bhaa_raceresult 
join race_mapping on race_mapping.bhaa_race_id=wp_bhaa_raceresult.race
set race = race_mapping.wp_race_id;
			
SELECT wp_bhaa_raceresult.*,race.id,wp_p2p.p2p_id FROM wp_bhaa_raceresult
join wp_posts as race on race.post_type='race' and race.id=wp_bhaa_raceresult.race
left join wp_p2p on (wp_p2p.p2p_from=race.id and wp_p2p.p2p_type='event_to_race')
where runner=7713 order by wp_bhaa_raceresult.race desc
left join wp_posts as event on (event.post_type='event' and event.id=wp_p2p.p2p_to)

-- update the race company details
update wp_bhaa_raceresult wp
join bhaaie_members.raceresult rr where rr.runner=wp.runner and rr.race=wp.race
set company=rr.company;

--sdcc2009 1523
update wp_bhaa_raceresult wp
join bhaaie_members.raceresult rr on (rr.runner=wp.runner and rr.race=.race)
join wp_bhaa_import import on (import.old=rr.race and import.type='race')
set company=rr.company
where wp.race=1523

select wp.*,rr.company from wp_bhaa_raceresult wp
join bhaaie_members.raceresult rr on (rr.runner=wp.runner and rr.race=
(select old from wp_bhaa_import where type='race' and new=wp.race))
where wp.race=1523

update wp_bhaa_raceresult wp
join bhaaie_members.raceresult rr on (rr.runner=wp.runner and rr.race=
(select old from wp_bhaa_import where type='race' and new=wp.race))
set company=rr.company

update wp_bhaa_raceresult wp
join bhaaie_members.raceresult rr on (rr.runner=wp.runner and rr.race=wp.race)
set wp.company=rr.company
where tt.race=201219;

SELECT id,name,tag,date,YEAR(date) as year,location FROM event order by id; desc

SELECT
race,
runner,
racetime,
position,
racenumber,
category,
raceresult.standard,
paceKM,
class
FROM raceresult 
JOIN runner on runner.id=raceresult.runner 
where raceresult.race>=0 and runner.status="M" and class="RAN" order by raceresult.race
				
select * from wp_bhaa_import where type='race'
select * from wp_bhaa_import where tag='dublinhalf2012'
select * from wp_bhaa_import where tag='ilp2011'

-- for the event and race id mapping between wp and members
desc wp_postmeta
select * from wp_postmeta
select distinct(meta_key) from wp_postmeta

select post_id,meta_value from wp_postmeta where meta_key='bhaa_race_id'
select post_id,meta_value from wp_postmeta where meta_key='bhaa_event_tag'

select post_id,meta_value,(select id from event where event.tag=meta_value)
from wp_postmeta where meta_key='bhaa_event_tag'			

			
SELECT wp_bhaa_raceresult.* FROM wp_bhaa_raceresult where runner=7713 order by race desc


SELECT wp_bhaa_raceresult.* FROM wp_bhaa_raceresult 
where runner=7713 order by race desc

select p2p_from from wp_p2p where p2p_to=207164 and wp_p2p.p2p_type="event_to_race"

SELECT wp_p2p.p2p_from as event,wp_bhaa_raceresult.* FROM wp_bhaa_raceresult 
join wp_p2p on (wp_p2p.p2p_to=wp_bhaa_raceresult.race and wp_p2p.p2p_type="event_to_race") 
where runner=7713 order by race desc


select * from wp_bhaa_teamresult
select * from wp_bhaa_import

select race,wp_bhaa_import.old,wp_bhaa_import.new
from wp_bhaa_teamresult
join wp_bhaa_import on (wp_bhaa_import.type='race' and wp_bhaa_import.old=wp_bhaa_teamresult.race)

update wp_bhaa_teamresult
join wp_bhaa_import on (wp_bhaa_import.type='race' and wp_bhaa_import.old=wp_bhaa_teamresult.race)
set race=wp_bhaa_import.new


-- event 206862
select p2p_to from wp_p2p where p2p_from=206862

SELECT wp_bhaa_teamresult.*,wp_posts.post_title as teamname 
FROM wp_bhaa_teamresult
join wp_posts on wp_posts.post_type="house" and wp_bhaa_teamresult.team=wp_posts.id
where race=(select p2p_to from wp_p2p where p2p_from=206862)
order by class, positiontotal


SELECT wp_bhaa_teamresult.*,wp_posts.post_title as teamname
FROM wp_bhaa_teamresult
join wp_posts on wp_posts.post_type="house" and wp_bhaa_teamresult.team=wp_posts.id
where race=201219 order by class, positiontotal

