
select * from bhaaie_members.teamraceresult where race=201282

select 
id,
team,
league,
(select new from wp_bhaa_import where type='race' and old=race) as race,
standardtotal,
positiontotal,
class,
leaguepoints,
status
from bhaaie_members.teamraceresult where race=201282

select * from wp_bhaa_teamresult
delete from wp_bhaa_teamresult

-- migrate the team results
insert into wp_bhaa_teamresult 
select 
id,
team,
league,
(select new from wp_bhaa_import where type='race' and old=race) as race,
standardtotal,
positiontotal,
class,
leaguepoints,
status
from bhaaie_members.teamraceresult where race=201282

select * from posts where

select * from wp_posts where post_type='event';

FROM  `wp_postmeta` 
WHERE  `meta_key` LIKE  'pyre_page_title'

INSERT INTO courses (name, location, gid)
SELECT name, location, 1
FROM   courses
WHERE  cid = 2

INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_title','yes' from wp_posts where post_type='event';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_full_width','no' from wp_posts where post_type='event';

INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_title','yes' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_full_width','no' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_sidebar_position','right' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_bg_repeat','repeat' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_slider_type','no' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_bg_full','no' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_bg','' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_bg_color','' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_fallback','' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_page_title_bar_bg','' from wp_posts where post_type='house';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'sbg_selected_sidebar_replacement','' from wp_posts where post_type='a:1:{i:0;s:1:"0";}';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'sbg_selected_sidebar','' from wp_posts where post_type='a:1:{i:0;s:1:"0";}';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_slider','' from wp_posts where post_type='0';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_wooslider','' from wp_posts where post_type='0';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_flexslider','' from wp_posts where post_type='0';
INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) 
select NULL,ID,'pyre_revslider','' from wp_posts where post_type='0';





insert into wp_postmeta() 
WHERE  meta_key LIKE  'pyre_page_title'


