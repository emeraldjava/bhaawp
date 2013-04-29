
select * from wp_bhaa_raceresult where race=2597;
select position, runner from wp_bhaa_raceresult where race=2597;

-- clean up NULL positions
select * from wp_bhaa_raceresult where position IS NULL
delete from wp_bhaa_raceresult where position IS NULL

update wp_bhaa_raceresult set runner=5253 where position=8 and race=2597;
-- 21 position?
