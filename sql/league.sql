
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