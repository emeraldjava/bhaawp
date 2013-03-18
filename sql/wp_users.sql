
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
select wp_users.id,status.meta_value,runner.id,runner.status from wp_users
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join bhaaie_members.runner runner on runner.id=wp_users.id
where runner.status!='M';
