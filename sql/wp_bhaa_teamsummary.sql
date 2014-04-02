-- wp_bhaa_teamsummary
DROP TABLE wp_bhaa_teamsummary;
CREATE TABLE wp_bhaa_teamsummary (
       race int(11) NOT NULL,
       team int(11) NOT NULL,
       teamname varchar(20),
       totalstd int(11) NOT NULL,
       position int(11) NOT NULL,
       leaguepoints double NOT NULL,
       PRIMARY KEY (race, team) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wp_bhaa_teamsummary
SELECT 
  race,
  team,
  teamname,
  min(totalpos) as totalpos,  
  min(position)as position,
  max(leaguepoints) as leaguepoints
FROM wp_bhaa_teamresult
WHERE position!=0
GROUP BY race,team;

SELECT * FROM wp_bhaa_teamsummary;
