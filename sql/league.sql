
INSERT INTO wp_bhaa_import (id, tag, type, new, old) VALUES
(NULL, 'winter2013', 'league', 2492, 13);

select * from wp_posts where post_type='league';

select * from bhaaie_members.league


select * from wp_bhaa_raceresult where runner=7713;

select * from wp_bhaa_raceresult where runner=7713;

select * from bhaaie_members.racepointsdata where runner=7713;

select * from bhaaie_members.racepoints where runner=7713;

select * from bhaaie_members.leaguesummary where leagueid=13;
update wp_bhaa_leaguesummary set league=2492 where league=13;

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguedivision,leagueposition,leaguescorecount,leaguepoints)
select
2492,"I",leagueparticipantid,leaguestandard,leaguedivision,leagueposition,leaguescorecount,leaguepoints
from bhaaie_members.leaguesummary where leagueid=13;



select event.tag,
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and class NOT IN ('RO','W','WO')) as total,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='A' group by race,class) as Amin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='A' group by race,class) as Amax,
getTeamStandardQuartile(1,race.id) as 'AQ1',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='A' group by race,class) as Ateams,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='B' group by race,class) as Bmin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='B' group by race,class) as Bmax,
getTeamStandardQuartile(2,race.id) as 'BQ2',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='B' group by race,class) as Bteams,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='C' group by race,class) as Cmin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='C' group by race,class) as Cmax,
getTeamStandardQuartile(3,race.id) as 'CQ3',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='C' group by race,class) as Cteams,
(select Min(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='D' group by race,class) as Dmin,
(select Max(standardTotal) from teamraceresult where teamraceresult.race=race.id and Class='D' group by race,class) as Dmax,
getTeamStandardQuartile(4,race.id) as 'DQ4',
(select COUNT(team) from teamraceresult where teamraceresult.race=race.id and Class='D' group by race,class) as Dteams
from race
join event on event.id=race.event
where race.id > 2010 and race.type IN ('M','C') and event.type != "track";
where race.id between 201100 and 201199 and race.type IN ('M','C') and event.type != "track";




