-- wp_bhaa_teamsummary
CREATE TABLE wp_bhaa_teamsummary (
       race int(11) NOT NULL,
       team int(11) NOT NULL,
       teamname varchar(20),
       totalstd int(11) NOT NULL,
       class varchar(1) NOT NULL, 
       position int(11) NOT NULL,
       leaguepoints double NOT NULL
);
