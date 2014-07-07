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
ALTER TABLE wp_bhaa_teamsummary ADD COLUMN class varchar(1) DEFAULT NULL AFTER totalstd;
ALTER TABLE wp_bhaa_teamsummary ADD COLUMN totalpos int(11) DEFAULT NULL AFTER totalstd;

DELETE FROM wp_bhaa_teamsummary
-- update the team summary table
INSERT INTO wp_bhaa_teamsummary
SELECT 
  race,
  team,
  teamname,
  min(totalstd) as totalstd,  
  min(totalpos) as totalpos,  
  class,
  min(position)as position,
  max(leaguepoints) as leaguepoints
FROM wp_bhaa_teamresult
WHERE position!=0
GROUP BY race,team
ORDER BY class,position;

SELECT * FROM wp_bhaa_teamsummary;
