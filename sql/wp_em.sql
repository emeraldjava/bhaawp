-- event manager SQL
-- annual membership 103, dcc 111, 

select * from wp_em_events where event_id=112

-- annual membership bookings
select * from wp_em_bookings where event_id=103

select booking_id,person_id,display_name,status.meta_value from wp_em_bookings
join wp_users on wp_users.id=person_id
join wp_usermeta status on (wp_em_bookings.person_id=status.user_id and status.meta_key='bhaa_runner_status')
where event_id=103 and status.meta_value != 'M'

update wp_usermeta
join wp_em_bookings on (wp_em_bookings.person_id=wp_usermeta.user_id and wp_em_bookings.booking_status=1 and wp_em_bookings.event_id=103)
set meta_value="M"
where wp_usermeta.meta_key='bhaa_runner_status';

-- find all event books
select * from wp_em_bookings where event_id=112

select booking_id,person_id,display_name,status.meta_value from wp_em_bookings
join wp_users on wp_users.id=person_id
left join wp_usermeta status on (wp_em_bookings.person_id=status.user_id and status.meta_key='bhaa_runner_status')
where event_id=112 
and (status.meta_value IS NULL or status.meta_value = '')

insert into wp_usermeta (user_id, meta_key, meta_value) VALUE (23019,'bhaa_runner_status','D');
-- where person_id in (23019,23289,23290,23301,23316,23327)
select * from wp_usermeta where user_id=23019;


-- find runner who paid annual but there status is not up to date
select status.meta_value,wp_em_bookings.* from wp_em_bookings 
left join wp_usermeta status on (wp_em_bookings.person_id=status.user_id and status.meta_key='bhaa_runner_status')
where event_id=103 and booking_status=1



select status.*,wp_em_bookings.* from wp_em_bookings 
left join wp_usermeta status on (wp_em_bookings.person_id=status.user_id and status.meta_key='bhaa_runner_status')
where event_id=112
and booking_price=15.00

--wp_em_bookings.booking_status=1 and
insert into wp_usermeta (user_id, meta_key, meta_value)
select wp_em_bookings.person_id,'bhaa_runner_status','D' from wp_em_bookings
where wp_em_bookings.event_id=112 and wp_em_bookings.booking_price=15.00;

select wp_em_bookings.person_id from wp_em_bookings
join wp_users on wp_users.id=wp_em_bookings.person_id
join wp_usermeta fn on (wp_em_bookings.person_id=fn.user_id and fn.meta_key='first_name')
join wp_usermeta ln on (wp_em_bookings.person_id=ln.user_id and ln.meta_key='last_name')
where wp_em_bookings.event_id=112 
and fn.meta_value="Peter" and ln.meta_value="xMooney"; 

update wp_usermeta
join wp_em_bookings on (wp_em_bookings.person_id=wp_usermeta.user_id and wp_em_bookings.booking_status=1)
set meta_value="M"
where wp_usermeta.meta_key='bhaa_runner_status' 
and wp_usermeta.meta_value IS NULL
and booking_price=10.00
and event_id=112;

UPDATE wp_usermeta SET meta_value="D" where meta_key="bhaa_runner_status" and user_id>=23051 and user_id<=23199;

