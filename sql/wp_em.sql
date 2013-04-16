-- event manager SQL
-- annual membership 103, dcc 111, 

select * from wp_em_events where event_id=112

-- annual membership bookings
select * from wp_em_bookings where event_id=103

-- find all event books
select * from wp_em_bookings where event_id=112

-- find runner who paid annual but there status is not up to date
select status.meta_value,wp_em_bookings.* from wp_em_bookings 
left join wp_usermeta status on (wp_em_bookings.person_id=status.user_id and status.meta_key='bhaa_runner_status')
where event_id=103 and booking_status=1

update wp_usermeta
join wp_em_bookings on (wp_em_bookings.person_id=wp_usermeta.user_id and wp_em_bookings.booking_status=1 and wp_em_bookings.event_id=103)
set meta_value="M"
where wp_usermeta.meta_key='bhaa_runner_status';

select status.*,wp_em_bookings.* from wp_em_bookings 
left join wp_usermeta status on (wp_em_bookings.person_id=status.user_id and status.meta_key='bhaa_runner_status')
where event_id=111
and booking_price=15.00

--wp_em_bookings.booking_status=1 and
insert into wp_usermeta (user_id, meta_key, meta_value)
select wp_em_bookings.person_id,'bhaa_runner_status','D' from wp_em_bookings
where wp_em_bookings.event_id=111 and wp_em_bookings.booking_price=15.00;


update wp_usermeta
join wp_em_bookings on (wp_em_bookings.person_id=wp_usermeta.user_id and wp_em_bookings.booking_status=1)
set meta_value="M"
where wp_usermeta.meta_key='bhaa_runner_status' 
and wp_usermeta.meta_value IS NULL
and booking_price=10.00
and event_id=111;

