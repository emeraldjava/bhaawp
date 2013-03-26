

-- get next event
select * from wp_em_events 
where event_start_date >= NOW()
order by event_start_date ASC
limit 1;

-- tidy up 
update wp_em_events
set event_start_date = '2013-01-01'
where event_id=103;
delete from wp_em_events where event_id=3;
