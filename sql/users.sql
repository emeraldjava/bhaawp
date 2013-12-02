
-- create table
create table gluser_bhaarunner (
gl_users_id int(11) NOT NULL,
bhaa_runner_id int(11) NOT NULL,
email varchar(50) NOT NULL)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table gluser_bhaarunner
select * from gluser_bhaarunner
delete from gluser_bhaarunner

-- insert matches
insert into gluser_bhaarunner 
select gl_users.uid,runner.id,runner.email from gl_users
join runner on runner.email=gl_users.email and runner.email!='';


-- UTIL
select uid,email from gl_users
select gl_users.uid,gl_users.email,runner.id,runner.email from gl_users
join runner on runner.email=gl_users.email and runner.email!='';

-- registrar email
select * from wp_users where user_email='registrar@bhaa.ie'
update wp_users set user_email=CONCAT(user_login,'@x.com') where user_email='registrar@bhaa.ie'

-- new users
select * from wp_users where user_registered > '2013-01-01';
select * from wp_users 
join wp_usermeta on wp_usermeta.user_id=wp_users.id
where user_registered > '2013-01-01'
and wp_usermeta.meta_key='bhaa_runner_status' 
and wp_usermeta.meta_value='M';

-- oct - dec 2012 members
select count(id) from bhaaie_members.runner where dateofrenewal>="2012-01-01";

select count(id) from bhaaie_members.runner where 
dateofrenewal between '2012-10-1' and '2012-12-31' 
and status='M' 

select count(id) from bhaaie_members.runner where dateofrenewal = '2013-01-01' 

update bhaaie_members.runner 
set dateofrenewal='2013-01-01'
where dateofrenewal between '2012-10-1' and '2012-12-31' 
and status='M' 

select MONTHNAME(dateofrenewal), count(id)
from bhaaie_members.runner 
where status='m'
and dateofrenewal>="2013-01-01"
group by MONTHNAME(dateofrenewal)
order by MONTH(dateofrenewal);

-- non renewed runners
select count(id) from runner where YEAR(dateofrenewal)=2012 and status="M";
update runner set status="I" where YEAR(dateofrenewal)=2012 and status="M";
select count(id) from runner where YEAR(dateofrenewal)=2013 and status="M"
    

select count(user_id) from wp_users
join wp_usermeta m1 on (
	m1.user_id=wp_users.id and 
	m1.meta_key='bhaa_runner_status' and
	m1.meta_value='M');
	
-- select all wordpress users who have renewed
select wp_users.id,wp_users.user_nicename from wp_users
join wp_usermeta m1 on (
	m1.user_id=wp_users.id and 
	m1.meta_key='bhaa_runner_status' and
	m1.meta_value='M')
join wp_usermeta m2 on (
	m2.user_id=wp_users.id and 
	m2.meta_key='bhaa_runner_dateofrenewal' and
	YEAR(m2.meta_value)='2013') order by id;


	
	
	-- 	INSERT INTO `runner`(`id`, `surname`, `firstname`, `gender`, `dateofbirth`, `company`, `email`, `mobilephone`,
	-- `status`, `insertdate`, `dateofrenewal`)

-- select new wordpress users
select wp_users.id,mfn.meta_value,mln.meta_value,mg.meta_value,mdob.meta_value,
mc.meta_value,wp_users.user_email,mp.meta_value,ms.meta_value,mdor.meta_value,mdor.meta_value from wp_users
join wp_usermeta ms on (
	ms.user_id=wp_users.id and 
	ms.meta_key='bhaa_runner_status' and
	ms.meta_value='M')
join wp_usermeta mfn on (
	mfn.user_id=wp_users.id and 
	mfn.meta_key='first_name') 
join wp_usermeta mln on (
	mln.user_id=wp_users.id and 
	mln.meta_key='last_name') 
join wp_usermeta mg on (
	mg.user_id=wp_users.id and 
	mg.meta_key='bhaa_runner_gender') 
join wp_usermeta mdob on (
	mdob.user_id=wp_users.id and 
	mdob.meta_key='bhaa_runner_dateofbirth') 
join wp_usermeta mc on (
	mc.user_id=wp_users.id and 
	mc.meta_key='bhaa_runner_company') 
join wp_usermeta mp on (
	mp.user_id=wp_users.id and 
	mp.meta_key='bhaa_runner_mobilephone') 
join wp_usermeta mdor on (
	mdor.user_id=wp_users.id and 
	mdor.meta_key='bhaa_runner_dateofrenewal') 	
where id>21000
order by id;

select * from bhaaie_members.runner where id>21000;
 	
INSERT INTO bhaaie_members.runner(id,firstname,surname,gender,dateofbirth,company,email,
mobilephone,status,insertdate,dateofrenewal)
 select wp_users.id,mfn.meta_value,mln.meta_value,mg.meta_value,mdob.meta_value,
mc.meta_value,wp_users.user_email,mp.meta_value,ms.meta_value,mdor.meta_value,mdor.meta_value from wp_users
join wp_usermeta ms on (
	ms.user_id=wp_users.id and 
	ms.meta_key='bhaa_runner_status' and
	ms.meta_value='M')
join wp_usermeta mfn on (
	mfn.user_id=wp_users.id and 
	mfn.meta_key='first_name') 
join wp_usermeta mln on (
	mln.user_id=wp_users.id and 
	mln.meta_key='last_name') 
join wp_usermeta mg on (
	mg.user_id=wp_users.id and 
	mg.meta_key='bhaa_runner_gender') 
join wp_usermeta mdob on (
	mdob.user_id=wp_users.id and 
	mdob.meta_key='bhaa_runner_dateofbirth') 
left join wp_usermeta mc on (
	mc.user_id=wp_users.id and 
	mc.meta_key='bhaa_runner_company') 
join wp_usermeta mp on (
	mp.user_id=wp_users.id and 
	mp.meta_key='bhaa_runner_mobilephone') 
join wp_usermeta mdor on (
	mdor.user_id=wp_users.id and 
	mdor.meta_key='bhaa_runner_dateofrenewal') 	
where id=22964
order by wp_users.id;


UPDATE bhaaie_members.runner
join wp_users wp_users on (
	wp_users.id=runner.id)
join wp_usermeta ms on (
	ms.user_id=wp_users.id and 
	ms.meta_key='bhaa_runner_status' and
	ms.meta_value='M')
join wp_usermeta mfn on (
	mfn.user_id=wp_users.id and 
	mfn.meta_key='first_name') 
join wp_usermeta mln on (
	mln.user_id=wp_users.id and 
	mln.meta_key='last_name') 
join wp_usermeta mg on (
	mg.user_id=wp_users.id and 
	mg.meta_key='bhaa_runner_gender') 
join wp_usermeta mdob on (
	mdob.user_id=wp_users.id and 
	mdob.meta_key='bhaa_runner_dateofbirth') 
left join wp_usermeta mc on (
	mc.user_id=wp_users.id and 
	mc.meta_key='bhaa_runner_company') 
join wp_usermeta mp on (
	mp.user_id=wp_users.id and 
	mp.meta_key='bhaa_runner_mobilephone') 
join wp_usermeta mdor on (
	mdor.user_id=wp_users.id and 
	mdor.meta_key='bhaa_runner_dateofrenewal') 	
set 
firstname=mfn.meta_value,
surname=mln.meta_value,
gender=mg.meta_value,
dateofbirth=mdob.meta_value,
company=mc.meta_value,
email=wp_users.user_email,
mobilephone=mp.meta_value,
status=ms.meta_value,
insertdate=mdor.meta_value,
dateofrenewal=mdor.meta_value
where wp_users.ID>21000;

-- update existing wp to members runners
UPDATE bhaaie_members.runner
join wp_users wp_users on (
	wp_users.id=runner.id)
join wp_usermeta ms on (
	ms.user_id=wp_users.id and 
	ms.meta_key='bhaa_runner_status' and
	ms.meta_value='M')
join wp_usermeta mdor on (
	mdor.user_id=wp_users.id and 
	mdor.meta_key='bhaa_runner_dateofrenewal' and
	YEAR(mdor.meta_value)='2013') 
set 
runner.status=ms.meta_value,
runner.dateofrenewal=mdor.meta_value
where wp_users.id>1500 and wp_users.id<10000;

-- runners with std 30!
select * from wp_usermeta where meta_key='bhaa_runner_standard' and meta_value=30
