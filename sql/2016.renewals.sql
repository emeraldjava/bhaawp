-- 2016.renewals.sql

select MONTHNAME(DATE(dor.meta_value)),YEAR(DATE(dor.meta_value)),count(m_status.umeta_id),count(i_status.umeta_id) from wp_users
left join wp_usermeta m_status on (m_status.user_id=wp_users.id and m_status.meta_key='bhaa_runner_status' and m_status.meta_value='M')
left join wp_usermeta i_status on (i_status.user_id=wp_users.id and i_status.meta_key='bhaa_runner_status' and i_status.meta_value='I')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
where DATE(dor.meta_value)>=DATE("2014-01-01")
group by MONTHNAME(DATE(dor.meta_value)), YEAR(DATE(dor.meta_value))
Order by YEAR(DATE(dor.meta_value)) DESC, MONTH(DATE(dor.meta_value));


select MONTHNAME(DATE(dor.meta_value)),YEAR(DATE(dor.meta_value)),count(m_status.umeta_id) from wp_users
join wp_usermeta m_status on (m_status.user_id=wp_users.id and m_status.meta_key='bhaa_runner_status')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
where m_status.meta_value='M'
AND DATE(dor.meta_value)>=DATE("2014-01-01")
group by MONTHNAME(DATE(dor.meta_value)), YEAR(DATE(dor.meta_value))
order by YEAR(DATE(dor.meta_value)) DESC, MONTH(DATE(dor.meta_value));


select wp_users.id, status.meta_value, MONTHNAME(DATE(dor.meta_value)),YEAR(DATE(dor.meta_value)) from wp_users
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status')
join wp_usermeta dor on (dor.user_id=wp_users.id and dor.meta_key='bhaa_runner_dateofrenewal')
where DATE(dor.meta_value)>=DATE("2014-01-01")
order by YEAR(DATE(dor.meta_value)) DESC, MONTH(DATE(dor.meta_value));
