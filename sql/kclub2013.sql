
select * from wp_bhaa_raceresult where race=2597;
select position, runner from wp_bhaa_raceresult where race=2597;

-- clean up NULL positions
select * from wp_bhaa_raceresult where position IS NULL
delete from wp_bhaa_raceresult where position IS NULL

update wp_bhaa_raceresult set runner=5253 where position=8 and race=2597;
-- 21 position?

SELECT wp_bhaa_raceresult.position,racenumber,wp_bhaa_raceresult.runner,wp_bhaa_raceresult.racetime,
lastname.meta_value as lastname,
firstname.meta_value as firstname,
CASE WHEN gender.meta_value='W' THEN 'F' ELSE 'M' END as gender,
dateofbirth.meta_value as dateofbirth,
standard,
'age' as age,
house.id as company, 
CASE WHEN house.post_title IS NULL THEN companyname.post_title ELSE house.post_title END as companyname,
CASE WHEN sector.id IS NOT NULL THEN sector.id ELSE house.id END as teamid,
CASE WHEN sector.post_title IS NOT NULL THEN sector.post_title ELSE house.post_title END as teamname
from wp_bhaa_raceresult
JOIN wp_p2p e2r ON (wp_bhaa_raceresult.race=e2r.p2p_to AND e2r.p2p_type="event_to_race")
JOIN wp_users on (wp_users.id=wp_bhaa_raceresult.runner) 
left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = 'house_to_runner')
left join wp_posts house on (house.id=r2c.p2p_from and house.post_type='house')
left join wp_p2p r2s ON (r2s.p2p_to=wp_users.id AND r2s.p2p_type = 'sectorteam_to_runner')
left join wp_posts sector on (sector.id=r2s.p2p_from and house.post_type='house')
left join wp_usermeta firstname ON (firstname.user_id=wp_users.id AND firstname.meta_key = 'first_name')
left join wp_usermeta lastname ON (lastname.user_id=wp_users.id AND lastname.meta_key = 'last_name')
left join wp_usermeta gender ON (gender.user_id=wp_users.id AND gender.meta_key = 'bhaa_runner_gender')
left join wp_usermeta dateofbirth ON (dateofbirth.user_id=wp_users.id AND dateofbirth.meta_key = 'bhaa_runner_dateofbirth')
left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')
left join wp_posts companyname on (companyname.id=company.meta_value and companyname.post_type='house')
where wp_bhaa_raceresult.class="RAN" 
AND wp_bhaa_raceresult.race=2597 order by wp_bhaa_raceresult.position

update wp_bhaa_raceresult set standardscoringset=NULL,posinsss=NULL,leaguepoints=NULL where race=2597;
select * from wp_bhaa_raceresult where race=2597
order by standardscoringset desc,position asc;

call updateRaceScoringSets(2597);
call updateRaceLeaguePoints(2597);



