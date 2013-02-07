
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
    