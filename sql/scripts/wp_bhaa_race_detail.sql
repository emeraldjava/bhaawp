
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

select * from wp_p2p where p2p_type='league_to_event' and p2p_from=3103;

select DISTINCT(post_type) from wp_posts;
select ID,post_title from wp_posts where post_type="league";

