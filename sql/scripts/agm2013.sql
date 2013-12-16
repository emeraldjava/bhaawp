-- event breakdown
select event_slug,
(select count(distinct(rr.runner)) from wp_bhaa_raceresult rr where rr.race in 
	(select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=post_id)) as total,
(select count(distinct(rr.runner)) from wp_bhaa_raceresult rr 
	JOIN wp_usermeta gender ON (gender.user_id=rr.runner AND gender.meta_key='bhaa_runner_gender')
	where rr.race in (select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=post_id)
	and gender.meta_value="M") as male,
(select count(distinct(rr.runner)) from wp_bhaa_raceresult rr 
	JOIN wp_usermeta gender ON (gender.user_id=rr.runner AND gender.meta_key='bhaa_runner_gender')
	where rr.race in (select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=post_id)
	and gender.meta_value="W") as female,
(select count(distinct(rr.runner)) from wp_bhaa_raceresult rr 
	JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key='bhaa_runner_status')
	where rr.race in (select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=post_id)
	and status.meta_value="M") as member,
(select count(distinct(rr.runner)) from wp_bhaa_raceresult rr 
	JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key='bhaa_runner_status')
	where rr.race in (select p2p_to from wp_p2p where p2p_type='event_to_race' and p2p_from=post_id)
	and status.meta_value!="M") as nonbhaa
from wp_em_events
where YEAR(event_start_date)=2013

-- 1080 members
select COUNT(DISTINCT(status.user_id)) from wp_usermeta status 
where status.meta_key='bhaa_runner_status'
and status.meta_value="M"
select COUNT(DISTINCT(status.user_id)) from wp_usermeta status 
where status.meta_key='bhaa_runner_status'
and status.meta_value="D"
select COUNT(DISTINCT(status.user_id)) from wp_usermeta status 
where status.meta_key='bhaa_runner_status'
and status.meta_value="I"

-- new BHAA members in 2013 341
select COUNT(DISTINCT(status.user_id))
from wp_usermeta status 
join wp_users u on (u.id=status.user_id)
where status.meta_key='bhaa_runner_status'
and status.meta_value="M"
and YEAR(u.user_registered)=2013

select ID,fn.meta_value,ln.meta_value,user_email from wp_users u
join wp_usermeta s on (s.user_id=u.id and s.meta_key='bhaa_runner_status' and s.meta_value="M")
join wp_usermeta fn on (fn.user_id=u.id and fn.meta_key='first_name')
join wp_usermeta ln on (ln.user_id=u.id and ln.meta_key='last_name')
where YEAR(u.user_registered)=2013

(select meta_value from wp_users where user_id=status.user_id AND meta_key='first_name') as fn,
(select meta_value from wp_users where user_id=status.user_id AND meta_key='last_name') as ln


left JOIN wp_usermeta fn ON (fn.user_id=u.id AND fn.meta_key='first_name')
left JOIN wp_usermeta ln ON (ln.user_id=u.id AND ln.meta_key='last_name')

-- all race results for 2013
select COUNT(id) from wp_bhaa_raceresult rr
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=rr.race)
join wp_em_events e on (e.post_id=e2r.p2p_from)
where YEAR(e.event_start_date)=2013 and rr.class='RAN'

-- age profile
select count(id) as runnercount, agecat, gender
from
(
    select ID,gender.meta_value as gender,getAgeCategory(dob.meta_value,curdate(),'M') as agecat from wp_users
	join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status')
	join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key='bhaa_runner_gender')
	join wp_usermeta dob on (dob.user_id=wp_users.id and dob.meta_key='bhaa_runner_dateofbirth')
	where status.meta_value="M"
) t1
group by t1.agecat,t1.gender
order by t1.agecat;

select ID,user_nicename,dob.meta_value,gender.meta_value,getAgeCategory(dob.meta_value,curdate(),'M') as agecat from wp_users
join wp_usermeta status on (status.user_id=wp_users.id and status.meta_key='bhaa_runner_status')
join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key='bhaa_runner_gender')
join wp_usermeta dob on (dob.user_id=wp_users.id and dob.meta_key='bhaa_runner_dateofbirth')
where status.meta_value="M";

-- dob format
select * from wp_usermeta where meta_value='18-02-74'
update wp_usermeta set meta_value='1982-07-09' where umeta_id=177381;
update wp_usermeta set meta_value='1974-02-18' where umeta_id=177413;

select *, (meta_value NOT REGEXP '^\d{4}-\d{1,2}-\d{1,2}') as regex from wp_usermeta where meta_key='bhaa_runner_dateofbirth';


select status.user_id,getagecategory(ru.dateofbirth, curdate(), ru.gender, 0) from wp_usermeta status 
where status.meta_key='bhaa_runner_status'
and status.meta_value="M"


select ru.id, getagecategory(ru.dateofbirth, curdate(), ru.gender, 0)  as agecat
    from runner ru 
    where ru.status='M' and ru.dateofrenewal >= '2011-1-1'

delete from wp_em_events where event_id in (3,2351)


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

-- league winners
select leaguedivision,leagueposition,leaguepoints,u.user_nicename,fn.meta_value,ln.meta_value,u.user_email,mobile.meta_value from wp_bhaa_leaguesummary 
join wp_users u on u.id=leagueparticipant
left JOIN wp_usermeta mobile ON (mobile.user_id=u.id AND mobile.meta_key='bhaa_runner_mobilephone')
left JOIN wp_usermeta fn ON (fn.user_id=u.id AND fn.meta_key='first_name')
left JOIN wp_usermeta ln ON (ln.user_id=u.id AND ln.meta_key='last_name')
where league=2659
and leagueposition<=10
order by leaguedivision,leagueposition