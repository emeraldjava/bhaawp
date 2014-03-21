
-- wp_bhaa_race_detail table
DROP TABLE wp_bhaa_race_detail
CREATE TABLE wp_bhaa_race_detail (
	id int(11) auto_increment primary key,
	league int(11) NULL,
	event int(11) NULL,
	eventname varchar(20) NULL,
	eventdate varchar(20) NULL,
	race int(11) NULL,
	racetype varchar(1) NULL,
	racedistance double
);

SELECT * FROM wp_bhaa_race_detail;
DELETE FROM wp_bhaa_race_detail;

select 
l2e.p2p_from as league,
event.ID as event,
event.post_title as eventname,
event.post_date as eventdate,
race.ID as race,
racetype.meta_value as racetype
from wp_p2p l2e
join wp_posts event on (l2e.p2p_to=event.ID)
join wp_p2p e2r on (l2e.p2p_to=e2r.p2p_from AND e2r.p2p_type='event_to_race')
join wp_posts race on (e2r.p2p_to=race.ID)
join wp_postmeta racetype on (race.ID=racetype.post_id AND racetype.meta_key='bhaa_race_type')
where l2e.p2p_type='league_to_event' and l2e.p2p_from=3103
ORDER BY eventdate;

select DISTINCT(meta_key) from wp_postmeta;
select DISTINCT(post_type) from wp_posts;
select ID,post_title from wp_posts where post_type="league";

