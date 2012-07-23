
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

