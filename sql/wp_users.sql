
-- select distinct gender values
select distinct(gender.meta_value) from wp_users u
join wp_usermeta gender on (gender.user_id=u.id AND gender.meta_key = 'bhaa_runner_gender');
 
 M - 6000
 W - 2765
 a:1:{i:0;s:1:"M";} - 20
  - 14
 a:1:{i:0;s:1:"W";} - 3
 a:2:{i:0;s:1:"M";i:1;s:1:"M";} - 1
 F - 10

select count(id) from wp_users u
join wp_usermeta gender on (gender.user_id=u.id AND gender.meta_key = 'bhaa_runner_gender')
where gender.meta_value='F';

update wp_usermeta set meta_value='W' where meta_key = 'bhaa_runner_gender' and meta_value='F';
update wp_usermeta set meta_value='W' where meta_key = 'bhaa_runner_gender' and meta_value='a:1:{i:0;s:1:"W";}';
update wp_usermeta set meta_value='M' where meta_key = 'bhaa_runner_gender' and meta_value='';
update wp_usermeta set meta_value='M' where meta_key = 'bhaa_runner_gender' and meta_value='a:1:{i:0;s:1:"M";}';
update wp_usermeta set meta_value='M' where meta_key = 'bhaa_runner_gender' and meta_value='a:2:{i:0;s:1:"M";i:1;s:1:"M";}';

-- dor dates 2013
select distinct(dor.meta_value) from wp_users u
join wp_usermeta dor on (dor.user_id=u.id AND dor.meta_key = 'bhaa_runner_dateofrenewal')
where YEAR(dor.meta_value)=2013 order by dor.meta_value desc;

-- select wp renewed but not upated in members
select wp_users.user_nicename,wp_users.id,status.meta_value,dor.meta_value,runner.id,runner.status,runner.dateofrenewal from wp_users
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
join bhaaie_members.runner runner on runner.id=wp_users.id
where runner.status!='M' and YEAR(dor.meta_value)=2013;

-- select wp users who are behind the members db
select wp_users.user_nicename,wp_users.id,status.meta_value,dor.meta_value,runner.id,runner.status,runner.dateofrenewal from wp_users
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
join bhaaie_members.runner runner on runner.id=wp_users.id
where runner.status='M' and status.meta_value='I';

update wp_users
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
join bhaaie_members.runner runner on runner.id=wp_users.id
set
runner.status=status.meta_value,
runner.dateofrenewal=dor.meta_value
where runner.status!='M' and YEAR(dor.meta_value)=2013;

-- handle new wp user not in the members db
select user_nicename,id,user_registered from wp_users where YEAR(user_registered)=2013;

-- list the two max id's
select MAX(id) from wp_users;
select MAX(id) from bhaaie_members.runner;

INSERT INTO bhaaie_members.runner(id,firstname,surname,gender,dateofbirth,company,email,mobilephone,status,insertdate,dateofrenewal)
select wp_users.id,mfn.meta_value,mln.meta_value,mg.meta_value,mdob.meta_value,mc.meta_value,wp_users.user_email,mp.meta_value,ms.meta_value,mdor.meta_value,mdor.meta_value from wp_users
join wp_usermeta ms on (
	ms.user_id=wp_users.id and 
	ms.meta_key='bhaa_runner_status')
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
where id>=22965
order by wp_users.id;

-- match runners with missing standards runner/raceresult
select * from wp_users
join wp_bhaa_raceresult on (wp_bhaa_raceresult.runner=wp_users.id and wp_bhaa_raceresult.class="RAN")
inner join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
inner join wp_usermeta standard on (standard.user_id=wp_users.id and standard.meta_key='bhaa_runner_standard')
where standard.meta_value=0 or standard.meta_value IS NULL

select * from wp_users
inner join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='D')
WHERE NOT EXISTS (
	SELECT * FROM wp_usermeta standard WHERE standard.user_id=wp_users.id and standard.meta_key='bhaa_runner_standard'
)

-- validate the wp_users status and dor
select u.id,u.user_nicename,dor.meta_value,status.meta_value from wp_users u
left join wp_usermeta dor on (dor.user_id=u.id AND dor.meta_key = 'bhaa_runner_dateofrenewal')
left join wp_usermeta status on (status.user_id=u.id AND status.meta_key = 'bhaa_runner_status')
where YEAR(dor.meta_value)!=2013 and status.meta_value='I' and u.id=5143;

-- 5143
update wp_usermeta
join wp_users on wp_users.id=wp_usermeta.user_id
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
set status.meta_value='I'
where YEAR(dor.meta_value)!=2013;

-- fix the usernames 22935,22821,22966 - 85 mismatched names
select id,display_name,first_name.meta_value,last_name.meta_value from wp_users 
left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key='first_name')
left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key='last_name')
where YEAR(user_registered)=2013 and id>1 and display_name!=CONCAT(first_name.meta_value,' ',last_name.meta_value);

update wp_users
left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key='first_name')
left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key='last_name')
set display_name=CONCAT(first_name.meta_value,' ',last_name.meta_value)
where YEAR(user_registered)=2013 and id>1 and display_name!=CONCAT(first_name.meta_value,' ',last_name.meta_value);

-- registration autocomplete query
select wp_users.id as id,wp_users.id as value,
wp_users.display_name as label,
first_name.meta_value as firstname,
last_name.meta_value as lastname,
status.meta_value as status,
gender.meta_value as gender,
dob.meta_value as dob,
company.meta_value as company,
standard.meta_value as standard
from wp_users
left join wp_usermeta first_name on (first_name.user_id=wp_users.id and first_name.meta_key='first_name')
left join wp_usermeta last_name on (last_name.user_id=wp_users.id and last_name.meta_key='last_name')
left join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status')
left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key='bhaa_runner_gender')
left join wp_usermeta dob on (dob.user_id=wp_users.id and dob.meta_key='bhaa_runner_dateofbirth')
left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key='bhaa_runner_company')
left join wp_usermeta standard on (standard.user_id=wp_users.id and standard.meta_key='bhaa_runner_standard')
where status.meta_value in ('M','I') order by id

