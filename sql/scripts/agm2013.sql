
select event_slug
from wp_em_events
where YEAR(event_start_date)=2013
	
delete from wp_em_events where event_id=3


-- all runners in an event
select count(distinct(rr.runner)) from wp_bhaa_raceresult rr where rr.race in 
(select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=2121)

-- member status M|D
select count(distinct(rr.runner)) from wp_bhaa_raceresult rr 
JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key='bhaa_runner_status')
where rr.race in (select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=2121)
and status.meta_value="D";

-- Gender M|W
select count(distinct(rr.runner)) from wp_bhaa_raceresult rr 
JOIN wp_usermeta gender ON (gender.user_id=rr.runner AND gender.meta_key='bhaa_runner_gender')
where rr.race in (select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=2121)
and gender.meta_value="W";

select * from wp_p2p where p2p_type='event_to_race' and p2p_from=2121

select * from wp_bhaa_raceresult where race=2359