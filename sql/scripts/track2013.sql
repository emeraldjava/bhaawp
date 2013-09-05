
-- event 2662 / 
-- summary event 2929 / race 2928

select wp_bhaa_raceresult.* from wp_bhaa_raceresult
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=2662)
where race= e2r.p2p_to

select race,runner,position,racenumber,category,standard,actualstandard,poststandard,MAX(leaguepoints) as leaguepoints,class from wp_bhaa_raceresult
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=2662)
where race= e2r.p2p_to
group by runner;

delete from wp_bhaa_raceresult where race=2928;
insert into wp_bhaa_raceresult (race,runner,position,racenumber,category,standard,actualstandard,poststandard,leaguepoints,class)
select 2928,runner,position,racenumber,category,standard,actualstandard,poststandard,MAX(leaguepoints) as leaguepoints,class 
from wp_bhaa_raceresult
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=2662)
where race= e2r.p2p_to
group by runner;

select * from wp_bhaa_raceresult where race=2928;

select * from wp_p2p e2r where e2r.p2p_type='event_to_race' and e2r.p2p_from=2662

-- update race type to track
update wp_postmeta set meta_value='TRACK' 
where meta_key='bhaa_race_type' and post_id in
(2850,2868,2914,2915,2916,2917,2918,2919,2920,2921,2922,2923,2924,2925,2926,2927);

-- link tcd summary race to event
INSERT INTO  `bhaaie_wp`.`wp_p2p` (
`p2p_id` ,
`p2p_from` ,
`p2p_to` ,
`p2p_type`
)
VALUES (
NULL ,  '2662',  '2928',  'event_to_race'
);

-- link tcd event to league
INSERT INTO  `bhaaie_wp`.`wp_p2p` (
`p2p_id` ,
`p2p_from` ,
`p2p_to` ,
`p2p_type`
)
VALUES (
NULL ,  '2659',  '2662',  'league_to_event'
);

select * from wp_bhaa_raceresult where race=2850 order by position
select * from wp_bhaa_raceresult where race=2928 order by position

call updateRace(2850);
call updateRace(2868);
call updateRace(2914);
call updateRace(2915);
call updateRace(2916);
call updateRace(2917);
call updateRace(2918);
call updateRace(2919);
call updateRace(2920);
call updateRace(2921);
call updateRace(2922);
call updateRace(2923);
call updateRace(2924);
call updateRace(2925);
call updateRace(2926);
call updateRace(2927);

-- BHAA MILE
select * from wp_p2p e2r where e2r.p2p_type='event_to_race' and e2r.p2p_from=2665
-- set the races to track
update wp_postmeta set meta_value='TRACK' 
where meta_key='bhaa_race_type' and post_id in
(2853,2964,2965,2966,2967,2968,2969,2971,2972);
-- link the summary race to the event
INSERT INTO  `bhaaie_wp`.`wp_p2p` (
`p2p_id` ,
`p2p_from` ,
`p2p_to` ,
`p2p_type`
)
VALUES (
NULL ,  '2665',  '3011',  'event_to_race'
);
-- link bhaa mile event to league
INSERT INTO  `bhaaie_wp`.`wp_p2p` (
`p2p_id` ,
`p2p_from` ,
`p2p_to` ,
`p2p_type`
)
VALUES (
NULL ,  '2659',  '2665',  'league_to_event'
);

update wp_bhaa_raceresult,wp_usermeta
set wp_bhaa_raceresult.standard=wp_usermeta.meta_value
where wp_usermeta.user_id=wp_bhaa_raceresult.runner
and wp_bhaa_raceresult.race in (2853,2964,2965,2966,2967,2968,2969,2971,2972)
and wp_usermeta.meta_key='bhaa_runner_standard'
and wp_usermeta.meta_value!='';

call updateRace(2853);
call updateRace(2964);
call updateRace(2965);
call updateRace(2966);
call updateRace(2967);
call updateRace(2968);
call updateRace(2969);
call updateRace(2971);
call updateRace(2972);

-- update summary race
delete from wp_bhaa_raceresult where race=3011
insert into wp_bhaa_raceresult (race,runner,position,racenumber,category,standard,actualstandard,poststandard,leaguepoints,class)
select 3011,runner,position,racenumber,category,standard,actualstandard,poststandard,MAX(leaguepoints) as leaguepoints,class 
from wp_bhaa_raceresult
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_from=2665)
where race= e2r.p2p_to
group by runner;



--  in (2853,2964,2965,2966,2967,2968,2969,2971,2972)
select * from wp_bhaa_raceresult where race=2972 order by position desc
select * from wp_users where id=6486
select * from wp_usermeta where user_id=6486

select * from wp_bhaa_raceresult where race=3011 order by position desc

delete from wp_p2p where p2p_id=2280
select * from wp_p2p e2r where e2r.p2p_type='league_to_event' and e2r.p2p_from=2659

select * from wp_bhaa_raceresult where runner = 7640 order by race desc
select * from wp_usermeta where user_id=7640

select wp_usermeta.meta_value,wp_bhaa_raceresult.* from wp_bhaa_raceresult 
join wp_usermeta on (wp_usermeta.user_id=wp_bhaa_raceresult.runner and wp_usermeta.meta_key='bhaa_runner_standard')
where race=2972 order by position desc
