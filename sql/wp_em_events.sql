

-- get next event
select event_id,post_id,event_slug from wp_em_events 
where event_start_date >= NOW()
order by event_start_date ASC limit 1;

-- get the next events race details
select e.event_id,e.post_id,e.event_slug,r.id,
r_dist.meta_value as dist,r_type.meta_value as type,r_unit.meta_value as unit 
from wp_em_events e
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e.post_id=e2r.p2p_from)
join wp_posts r on (r.id=e2r.p2p_to)
inner join wp_postmeta r_dist on (r_dist.post_id=r.id and r_dist.meta_key='bhaa_race_distance')
inner join wp_postmeta r_type on (r_type.post_id=r.id and r_type.meta_key='bhaa_race_type')
inner join wp_postmeta r_unit on (r_unit.post_id=r.id and r_unit.meta_key='bhaa_race_unit')
where event_start_date >= NOW()
order by event_start_date ASC, dist DESC limit 2;

-- get last event
select *,wp_postmeta.meta_value as tag from wp_em_events 
left join wp_postmeta on (wp_postmeta.post_id=wp_em_events.post_id and wp_postmeta.meta_key='bhaa_event_tag')
where event_start_date <= NOW()
order by event_start_date DESC limit 1;

select * from wp_bhaa_raceresult 
join wp_posts r on (r.id=wp_bhaa_raceresult.race) 
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.id)
where class="RACE_REG" and e2r.p2p_from=2278

-- tidy up 
update wp_em_events
set event_start_date = '2013-01-01'
where event_id=103;
delete from wp_em_events where event_id=3;
