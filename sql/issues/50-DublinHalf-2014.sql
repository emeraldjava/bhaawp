-- 50-DublinHalf-2014.sql


INSERT INTO wp_bhaa_raceresult(race,runner,racetime,position,racenumber,category,standard,class,company)
SELECT 3841,2075,'01:00:00',1,2075,
getAgeCategory(
	(SELECT meta_value from wp_usermeta WHERE user_id=2075 and meta_key='bhaa_runner_dateofbirth'),
	'2014-09-20',
	(SELECT meta_value from wp_usermeta WHERE user_id=2075 and meta_key='bhaa_runner_gender')) as agecat,
(SELECT meta_value from wp_usermeta WHERE user_id=2075 and meta_key='bhaa_runner_standard') as standard,
'RAN',
(SELECT meta_value from wp_usermeta WHERE user_id=2075 and meta_key='bhaa_runner_company') as company
FROM wp_users

-- do the teams
