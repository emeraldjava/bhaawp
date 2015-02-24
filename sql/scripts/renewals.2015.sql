-- renewals 2015

select MONTHNAME(DATE(dor.meta_value)),count(id)

-- autum 2014 runners 
select u.id,u.user_email,status.meta_value,dor.meta_value
from wp_users u
join wp_usermeta status on (status.user_id=u.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=u.id and dor.meta_key='bhaa_runner_dateofrenewal')
where status.meta_value='M'
AND DATE(dor.meta_value)>=DATE("2014-10-01")
AND DATE(dor.meta_value)<=DATE("2015-01-10")

-- jan 2015 runners 
select u.id,u.user_email,status.meta_value,dor.meta_value
from wp_users u
join wp_usermeta status on (status.user_id=u.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=u.id and dor.meta_key='bhaa_runner_dateofrenewal')
where status.meta_value='M'
AND DATE(dor.meta_value)>=DATE("2015-01-01")
AND DATE(dor.meta_value)<=DATE("2015-02-10")

select u.id,u.user_email,status.meta_value,dor.meta_value
from wp_users u
join wp_usermeta status on (status.user_id=u.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=u.id and dor.meta_key='bhaa_runner_dateofrenewal')
where status.meta_value='M'
AND DATE(dor.meta_value)>=DATE("2013-01-01")
AND DATE(dor.meta_value)<=DATE("2014-09-30")

select u.user_email
from wp_users u
join wp_usermeta status on (status.user_id=u.id and status.meta_key='bhaa_runner_status' and status.meta_value='M')
join wp_usermeta dor on (dor.user_id=u.id and dor.meta_key='bhaa_runner_dateofrenewal')
where status.meta_value='M'
AND DATE(dor.meta_value)>=DATE("2013-01-01")
AND DATE(dor.meta_value)<=DATE("2014-09-30")
AND u.user_email IS NOT NULL
AND u.user_email !=''





