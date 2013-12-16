
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

