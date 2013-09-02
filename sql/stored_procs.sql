-- bhaa stored proces

-- http://www.coderrants.com/wordpress-and-stored-procedures/
-- http://wordpress.org/support/topic/how-to-call-stored-procedure-from-plugin

SET GLOBAL log_bin_trust_function_creators = 1;

DELIMITER $$

-- only functions return values
DROP FUNCTION IF EXISTS `getUsername`$$
CREATE FUNCTION `getUsername`(_runner INT) RETURNS int(11)
BEGIN
DECLARE _result int;
SET _result  = (SELECT user_nicename FROM wp_users WHERE id = _runner);
RETURN _result;
END $$

-- getRaceDistanceKm
DROP FUNCTION IF EXISTS `getRaceDistanceKm`$$
CREATE FUNCTION `getRaceDistanceKm`(_race INT) RETURNS double
BEGIN
DECLARE _unit VARCHAR(5);
DECLARE _distance DOUBLE;
SET _unit = (select meta_value from wp_postmeta where post_id=_race and meta_key='bhaa_race_unit');
SET _distance = (select meta_value from wp_postmeta where post_id=_race and meta_key='bhaa_race_distance');
SET _distance = IF (_unit = 'Mile', _distance * 1.609344, _distance);
RETURN _distance;
END $$

-- getStandard
DROP FUNCTION IF EXISTS `getStandard`$$
CREATE FUNCTION `getStandard`(_raceTime TIME, _distanceKm DOUBLE) RETURNS int(11)
BEGIN
DECLARE _standard INT DEFAULT 1;
SET _standard = (
SELECT S.Standard
FROM
(
SELECT Standard, SEC_TO_TIME((((wp_bhaa_standard.slopefactor)*(_distanceKm-1)) + wp_bhaa_standard.oneKmTimeInSecs) * _distanceKm) as Expected
FROM wp_bhaa_standard
WHERE Standard <> 1
UNION ALL SELECT 1 AS Standard , SEC_TO_TIME(1) AS Expected
) S
WHERE S.Expected <= _raceTime
ORDER BY S.Standard DESC LIMIT 1
);
RETURN COALESCE(_standard, 30);
END $$

-- getAgeCategory
DROP FUNCTION IF EXISTS `getAgeCategory`$$
CREATE FUNCTION `getAgeCategory`(_birthDate DATE, _currentDate DATE, _gender ENUM('M','W')) RETURNS varchar(4) CHARSET utf8
BEGIN
DECLARE _age INT(11);
SET _age = (YEAR(_currentDate)-YEAR(_birthDate)) - (RIGHT(_currentDate,5)<RIGHT(_birthDate,5));
RETURN (SELECT category FROM wp_bhaa_agecategory WHERE (_age between min and max) and gender=_gender);
END$$

-- updatePositions by time
DROP PROCEDURE IF EXISTS `updatePositions`$$
CREATE PROCEDURE `updatePositions`(_race INT(11))
BEGIN
	
	create temporary table if not exists tmp_raceresult(
    	race int,
        runner int,
        position int
    ) ENGINE=MyISAM;
      
    insert into tmp_raceresult
		select race, runner, @row:=@row+1
	    from wp_bhaa_raceresult, (SELECT @row:=0) r
	    where race=_race order by racetime;
	    
	alter table tmp_raceresult add index (race,runner);
	
	update wp_bhaa_raceresult, tmp_raceresult
    	set wp_bhaa_raceresult.position=tmp_raceresult.position
    	where wp_bhaa_raceresult.runner=tmp_raceresult.runner 
    	and wp_bhaa_raceresult.race=tmp_raceresult.race;

    drop table tmp_raceresult;

END$$

-- updatePositionInStandard
DROP PROCEDURE IF EXISTS `updatePositionInStandard`$$
CREATE PROCEDURE `updatePositionInStandard`(_raceId INT(11))
BEGIN

DECLARE _nextstandard INT(11);
   DECLARE no_more_rows BOOLEAN;
   DECLARE loop_cntr INT DEFAULT 0;
   DECLARE num_rows INT DEFAULT 0;
   DECLARE _standardCursor CURSOR FOR select standard from wp_bhaa_standard;
   DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_rows = TRUE;

   OPEN _standardCursor;
   SELECT FOUND_ROWS() into num_rows;

    the_loop: LOOP

    FETCH _standardCursor INTO _nextstandard;

    IF no_more_rows THEN
        CLOSE _standardCursor;
        LEAVE the_loop;
    END IF;
   CREATE TEMPORARY TABLE tmpStandardRaceResult(actualposition INT PRIMARY KEY AUTO_INCREMENT, runner INT);
    INSERT INTO tmpStandardRaceResult(runner)
    SELECT runner
    FROM wp_bhaa_raceresult
    WHERE race = _raceId AND standard = _nextstandard and class='RAN';

    UPDATE wp_bhaa_raceresult, tmpStandardRaceResult
    SET wp_bhaa_raceresult.posinstd = tmpStandardRaceResult.actualposition
    WHERE wp_bhaa_raceresult.runner = tmpStandardRaceResult.runner AND wp_bhaa_raceresult.race = _raceId;

    DELETE FROM tmpStandardRaceResult;

    SET loop_cntr = loop_cntr + 1;

  DROP TEMPORARY TABLE tmpStandardRaceResult;

  END LOOP the_loop;
END$$

-- updatePositionInAgeCategory
DROP PROCEDURE IF EXISTS `updatePositionInAgeCategory`$$
CREATE PROCEDURE `updatePositionInAgeCategory`(_raceId INT(11))
BEGIN

 DECLARE _nextCategory VARCHAR(6);
   DECLARE no_more_rows BOOLEAN;
   DECLARE loop_cntr INT DEFAULT 0;
   DECLARE num_rows INT DEFAULT 0;
   DECLARE _catCursor CURSOR FOR select category from wp_bhaa_agecategory;
   DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_rows = TRUE;
   OPEN _catCursor;
   SELECT FOUND_ROWS() into num_rows;
    the_loop: LOOP
    FETCH _catCursor INTO _nextCategory;
    IF no_more_rows THEN
        CLOSE _catCursor;
        LEAVE the_loop;
    END IF;
   CREATE TEMPORARY TABLE tmpCategoryRaceResult(actualposition INT PRIMARY KEY AUTO_INCREMENT, runner INT);
    INSERT INTO tmpCategoryRaceResult(runner)
    SELECT runner
    FROM wp_bhaa_raceresult
    WHERE race = _raceId AND category = _nextCategory AND class='RAN';
    UPDATE wp_bhaa_raceresult, tmpCategoryRaceResult
    SET wp_bhaa_raceresult.posincat = tmpCategoryRaceResult.actualposition
    WHERE wp_bhaa_raceresult.runner = tmpCategoryRaceResult.runner AND wp_bhaa_raceresult.race = _raceId;
    DELETE FROM tmpCategoryRaceResult;
    SET loop_cntr = loop_cntr + 1;
  DROP TEMPORARY TABLE tmpCategoryRaceResult;
  END LOOP the_loop;
END$$

-- updatePostRaceStandard
DROP PROCEDURE IF EXISTS `updatePostRaceStandard`$$
CREATE PROCEDURE `updatePostRaceStandard`(_raceId INT)
BEGIN
UPDATE wp_bhaa_raceresult rr_outer,
(
SELECT rr.race,rr.runner,rr.racetime,NULLIF(rr.standard,0) as standard,rr.actualstandard
FROM wp_bhaa_raceresult rr
WHERE rr.race = _raceId AND rr.class='RAN'
) t
SET rr_outer.poststandard =
CASE
  WHEN t.standard IS NULL
	  THEN t.actualstandard
  WHEN t.standard  < t.actualstandard
	  THEN t.standard  + 1
  WHEN t.standard  > t.actualstandard
	  THEN t.standard  - 1
  WHEN t.standard  = t.actualstandard
    THEN t.standard
END
WHERE rr_outer.race = t.race AND rr_outer.runner=t.runner;
-- update 'bhaa_runner_standard' meta field
UPDATE wp_bhaa_raceresult, wp_usermeta
SET wp_usermeta.meta_value = wp_bhaa_raceresult.poststandard
WHERE wp_bhaa_raceresult.runner = wp_usermeta.user_id
AND wp_usermeta.meta_key='bhaa_runner_standard'
AND wp_bhaa_raceresult.race = _raceId
AND COALESCE(wp_usermeta.meta_value,0) <> wp_bhaa_raceresult.poststandard;
END$$

-- updateRaceScoringSets
DROP PROCEDURE IF EXISTS `updateRaceScoringSets`$$
CREATE PROCEDURE `updateRaceScoringSets`(_race INT)
  BEGIN
 DECLARE _raceType ENUM('m','w','c');
 DECLARE _scoringthreshold int;
 DECLARE _currentstandard int;
 DECLARE _runningtotal int;
 DECLARE _runningtotalM int;
 DECLARE _runningtotalW int;
 DECLARE _scoringset int;
 DECLARE _scoringsetM int;
 DECLARE _scoringsetW int;

    -- TODO look this up from the league
    SET _scoringthreshold = 4;
    SET _currentstandard  = 30;
    SET _runningtotal     = 0;
    SET _runningtotalM    = 0;
    SET _runningtotalW    = 0;
    SET _scoringset       = 1;
    SET _scoringsetM      = 1;
    SET _scoringsetW      = 1;

    UPDATE wp_bhaa_raceresult
    SET    standardscoringset = NULL
    WHERE  race = _race;

    CREATE TEMPORARY TABLE IF NOT EXISTS scoringstandardsets(
        standard int,
        gender ENUM('M','W') NULL,
        standardcount int NULL,
        scoringset int NULL);

    SET _raceType         = (SELECT meta_value
                             FROM   wp_postmeta
                             WHERE  post_id = _race AND meta_key = 'bhaa_race_type');
                          
    /* Process Gender Race. In this case Gender is relevant as all runners
       are grouped together based on their standard and gender. */
    IF (_raceType = 'C')
    THEN
      INSERT INTO scoringstandardsets(standard, standardcount, scoringset, gender)
        SELECT standard, 0, 0, 'W' FROM wp_bhaa_standard
        UNION
        SELECT standard, 0, 0, 'M' FROM wp_bhaa_standard;

      UPDATE scoringstandardsets ss,
             (SELECT   r.standard, gender.meta_value AS gender, count(r.standard) AS standardcount
              FROM       wp_bhaa_raceresult r
              JOIN wp_usermeta gender ON (gender.user_id = r.runner AND gender.meta_key = 'bhaa_runner_gender')
              WHERE    r.race = _race AND COALESCE(r.standard, 0) > 0
              GROUP BY r.standard, gender.meta_value) x
      SET    ss.standardcount = x.standardcount 
      WHERE  ss.standard = x.standard AND ss.gender = x.gender;

      WHILE (_currentstandard > 0)
      DO
        SET _runningtotalM      = (SELECT sum(standardcount)
                                   FROM   scoringstandardsets
                                   WHERE  standard >= _currentstandard AND scoringset = 0 AND gender = 'M');

        SET _runningtotalW      = (SELECT sum(standardcount)
                                   FROM   scoringstandardsets
                                   WHERE  standard >= _currentstandard AND scoringset = 0 AND gender = 'W');

        IF (_runningtotalM >= _scoringthreshold)
        THEN
          UPDATE scoringstandardsets
          SET    scoringset = _scoringsetM
          WHERE  standard >= _currentstandard AND scoringset = 0 AND gender = 'M';

          SET _scoringsetM   = _scoringsetM + 1;
          SET _runningtotalM = 0;
        END IF;

        IF (_runningtotalW >= _scoringthreshold)
        THEN
          UPDATE scoringstandardsets
          SET    scoringset = _scoringsetW
          WHERE  standard >= _currentstandard AND scoringset = 0 AND gender = 'W';

          SET _scoringsetW   = _scoringsetW + 1;
          SET _runningtotalW = 0;
        END IF;

        SET _currentstandard    = _currentstandard - 1;
      END WHILE;

      UPDATE scoringstandardsets
      SET    scoringset = _scoringsetW
      WHERE  scoringset = 0 AND gender = 'W';

      UPDATE scoringstandardsets
      SET    scoringset = _scoringsetM
      WHERE  scoringset = 0 AND gender = 'M';

      UPDATE wp_bhaa_raceresult
      JOIN scoringstandardsets ON scoringstandardsets.standard=wp_bhaa_raceresult.standard
      JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_raceresult.runner AND gender.meta_key = 'bhaa_runner_gender')
      SET wp_bhaa_raceresult.standardscoringset = scoringstandardsets.scoringset
      WHERE gender.meta_value = scoringstandardsets.gender AND wp_bhaa_raceresult.race = _race;
      
    ELSE
      /* Process Gender Race. In this case Gender is irrelevant as all runners
         are grouped together based on their standards only. */
      INSERT INTO scoringstandardsets(standard, standardcount, scoringset)
        SELECT   standard, 0, 0
        FROM     wp_bhaa_standard
        ORDER BY standard DESC;

      /* Count the total runners in each standard and update the temporary table
         1. Ignore Gender 2. Ignore null and Standard 0
      */
      UPDATE scoringstandardsets ss,
             (SELECT   r.standard, count(r.standard) AS standardcount
              FROM     wp_bhaa_raceresult r
              WHERE    r.race = _race AND COALESCE(r.standard, 0) > 0
              GROUP BY r.standard) x
      SET    ss.standardcount = x.standardcount
      WHERE  ss.standard = x.standard;

      WHILE (_currentstandard > 0)
      DO
        SET _runningtotal      = (SELECT sum(standardcount)
                                  FROM   scoringstandardsets
                                  WHERE  standard >= _currentstandard AND scoringstandardsets.scoringset = 0);

        IF (_runningtotal >= _scoringthreshold)
        THEN
          UPDATE scoringstandardsets
          SET    scoringset = _scoringset
          WHERE  standard >= _currentStandard AND scoringset = 0;
          

          SET _scoringset   = _scoringset + 1;
          SET _runningtotal = 0;
        END IF;

        SET _currentstandard   = _currentstandard - 1;
      END WHILE;

      /* Left overs get added to last scoring set */
      UPDATE scoringstandardsets
      SET    scoringset = _scoringset
      WHERE  scoringset = 0;

      -- SELECT * FROM scoringstandardsets;

      UPDATE wp_bhaa_raceresult, scoringstandardsets
      SET    wp_bhaa_raceresult.standardscoringset = scoringstandardsets.scoringset
      WHERE  scoringstandardsets.standard = wp_bhaa_raceresult.standard and wp_bhaa_raceresult.race = _race;
    END IF;

    DROP TABLE scoringstandardsets;
END$$

-- updateRaceLeaguePoints
DROP PROCEDURE IF EXISTS `updateRaceLeaguePoints`$$
CREATE PROCEDURE `updateRaceLeaguePoints`(_race INT )
BEGIN
	create temporary table if not exists  tmpPosInSet (
        id int auto_increment,
        scoringset int,
        runner int,
        primary key(scoringset,id)
      )ENGINE=MyISAM;
      
    insert into tmpPosInSet(runner,scoringset)  
        select runner,standardscoringset
        from wp_bhaa_raceresult
        where race = _race and wp_bhaa_raceresult.standardscoringset IS NOT NULL
        order by position asc;
 
    update wp_bhaa_raceresult, tmpPosInSet
    set 
    wp_bhaa_raceresult.leaguepoints = (10.1 - (tmpPosInSet.id * 0.1)),
    wp_bhaa_raceresult.posinsss = tmpPosInSet.id
    where wp_bhaa_raceresult.runner = tmpPosInSet.runner and wp_bhaa_raceresult.race = _race;

    drop table tmpPosInSet;
END$$

-- updateRace
DROP PROCEDURE IF EXISTS `updateRace`$$
CREATE PROCEDURE `updateRace`(_race INT )
BEGIN
	CALL updatePositionInAgeCategory(_race);
	CALL updatePositionInStandard(_race);
	CALL updateRaceScoringSets(_race);
	CALL updateRaceLeaguePoints(_race);
END$$

-- updateLeagueData
DROP PROCEDURE IF EXISTS `updateLeagueData`$$
CREATE PROCEDURE `updateLeagueData`(_leagueId INT(11))
BEGIN

DELETE FROM wp_bhaa_leaguesummary WHERE league=_leagueId;

INSERT INTO wp_bhaa_leaguesummary(league,leaguetype,leagueparticipant,leaguestandard,leaguescorecount,leaguepoints,
leaguedivision,leagueposition,leaguesummary)
SELECT
le.id,
'I',
rr.runner,
ROUND(AVG(rr.standard),0),
COUNT(rr.race),
ROUND(getLeaguePointsTotal(le.id,rr.runner),1) as leaguepoints,
'A',
1,
GROUP_CONCAT( cast( concat_ws(':',e.ID,rr.leaguepoints,IF(class='RACE_ORG','RO',NULL)) AS char ) SEPARATOR ',') AS leaguesummary
FROM wp_bhaa_raceresult rr
inner join wp_posts r ON rr.race = r.id
inner join wp_postmeta rt on (rt.post_id=r.id and rt.meta_key = 'bhaa_race_type')
inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
inner join wp_posts e ON e2r.p2p_from = e.id
inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
inner JOIN wp_posts le ON l2e.p2p_from = le.id
inner JOIN wp_users ru ON rr.runner = ru.id
JOIN wp_usermeta status ON (status.user_id=rr.runner AND status.meta_key = 'bhaa_runner_status')
JOIN wp_usermeta standard ON (standard.user_id=rr.runner AND standard.meta_key = 'bhaa_runner_standard')
WHERE le.id=_leagueId AND class in ('RAN','RACE_ORG') 
AND standard.meta_value IS NOT NULL AND status.meta_value='M'
AND rt.meta_value!='TRACK' -- exclude TRACK events
GROUP BY le.id,rr.runner
HAVING COALESCE(leaguepoints, 0) > 0;
  
update wp_bhaa_leaguesummary 
JOIN wp_usermeta gender ON (gender.user_id=wp_bhaa_leaguesummary.leagueparticipant AND gender.meta_key = 'bhaa_runner_gender')
JOIN wp_bhaa_division d ON ((wp_bhaa_leaguesummary.leaguestandard BETWEEN d.min AND d.max) AND d.type='I' and d.gender=gender.meta_value)
set wp_bhaa_leaguesummary.leaguedivision=d.code
where league=_leagueId;

SET @a=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@a:= (@a+1)) where leaguedivision="A" and league=_leagueId ORDER BY leaguepoints DESC;
SET @b=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@b:= (@b+1)) where leaguedivision="B" and league=_leagueId ORDER BY leaguepoints DESC;
SET @c=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@c:= (@c+1)) where leaguedivision="C" and league=_leagueId ORDER BY leaguepoints DESC;
SET @d=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@d:= (@d+1)) where leaguedivision="D" and league=_leagueId ORDER BY leaguepoints DESC;
SET @e=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@e:= (@e+1)) where leaguedivision="E" and league=_leagueId ORDER BY leaguepoints DESC;
SET @f=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@f:= (@f+1)) where leaguedivision="F" and league=_leagueId ORDER BY leaguepoints DESC;
SET @g=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@g:= (@g+1)) where leaguedivision="L1" and league=_leagueId ORDER BY leaguepoints DESC;
SET @h=0;
UPDATE wp_bhaa_leaguesummary SET leagueposition=(@h:= (@h+1)) where leaguedivision="L2" and league=_leagueId ORDER BY leaguepoints DESC;

END$$

-- getLeaguePointsTotal                       
DROP FUNCTION IF EXISTS `getLeaguePointsTotal`$$
CREATE FUNCTION `getLeaguePointsTotal`(_leagueId INT(11), _runnerId INT(11)) RETURNS double
BEGIN

DECLARE _pointsTotal DOUBLE;
DECLARE _racesToCount INT;

SET _racesToCount = 8; -- (select racestoscore from league where id=_leagueId);

SET _pointsTotal =
(
        SELECT SUM(points) FROM
(
      SELECT points ,@rownum:=@rownum+1 AS bestxpoints
      FROM
      (
      SELECT
      DISTINCT e.id,
      CASE rr.leaguepoints WHEN 11 THEN 10 ELSE rr.leaguepoints END AS points
      FROM wp_bhaa_raceresult rr
      inner join wp_posts r ON rr.race = r.id
      inner join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=r.ID)
      inner join wp_posts e ON e2r.p2p_from = e.id
      inner join wp_p2p l2e on (l2e.p2p_type='league_to_event' and l2e.p2p_to=e.ID)
      inner JOIN wp_posts le ON l2e.p2p_from = le.id
	  WHERE runner=_runnerId AND le.id=_leagueId 
	  and rr.class in ('RAN', 'RACE_ORG', 'RACE_POINTS') order by rr.leaguepoints desc) r1, (SELECT @rownum:=0) r2
) t where t.bestxpoints <= _racesToCount 
);

RETURN _pointsTotal;

END$$

-- updateTeamLeagueSummary
DROP PROCEDURE IF EXISTS `updateTeamLeagueSummary`$$
CREATE PROCEDURE `updateTeamLeagueSummary`(_leagueId  INT)
BEGIN

update wp_bhaa_teamresult set leaguepoints=(7-(position)) where class!='R';
update wp_bhaa_teamresult set leaguepoints=1 where leaguepoints<=0 and class!='R';

DELETE FROM wp_bhaa_leaguesummary WHERE leagueType='T' and league = _leagueId;

INSERT INTO wp_bhaa_leaguesummary(
	league,
	leaguetype,
	leagueparticipant,
	leaguestandard,
	leaguedivision,
	leagueposition,
	leaguescorecount,
	leaguepoints,
	leaguesummary)
SELECT
t1.league,
t1.leaguetype,
t1.leagueparticipant,
t1.leaguestandard as leaguestandard,
'W' AS leaguedivision,
@rownum:=@rownum+1 AS leagueposition,
t1.leaguescorecount,
t1.leaguepoints as leaguepoints,
t1.leaguesummary AS leaguesummary
FROM
(
SELECT
_leagueId AS league,
'T' AS leaguetype,
l.team AS leagueparticipant,
0 AS leaguestandard,
0 AS leaguedivision,
SUM(l.leaguescorecount) AS leaguescorecount,
SUM(l.leaguepoints) AS leaguepoints,
GROUP_CONCAT( cast( concat_ws(':',l.event,l.leaguepoints,IF(l.class='R','RO',NULL)) AS char ) SEPARATOR ',') AS leaguesummary
FROM
(
SELECT 1 AS leaguescorecount, team, race, class, MAX(leaguepoints) AS leaguepoints, e2r.p2p_from as event
FROM wp_bhaa_teamresult trr
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=trr.race)
WHERE class in ('W','R')
GROUP BY team,race
) l
GROUP BY l.team
ORDER BY leaguepoints DESC
)t1, (SELECT @rownum:=0) t2;

-- t1.leaguepoints - (SELECT count(1) FROM wp_bhaa_teamresult where team = t1.leagueparticipant and class='R') as leaguepoints,
INSERT INTO wp_bhaa_leaguesummary(
	league,
	leaguetype,
	leagueparticipant,
	leaguestandard,
	leaguedivision,
	leagueposition,
	leaguescorecount,
	leaguepoints,
	leaguesummary)
SELECT
t1.league,
t1.leaguetype,
t1.leagueparticipant,
t1.leaguestandard as leaguestandard,
'M' AS leaguedivision,
@rownum:=@rownum+1 AS leagueposition,
t1.leaguescorecount,
t1.leaguepoints as leaguepoints,
t1.leaguesummary AS leaguesummary
FROM
(
SELECT
_leagueId AS league,
'T' AS leaguetype,
l.team AS leagueparticipant,
0 AS leaguestandard,
0 AS leaguedivision,
SUM(l.leaguescorecount) AS leaguescorecount,
SUM(l.leaguepoints) AS leaguepoints,
GROUP_CONCAT( cast( concat_ws(':',l.event,l.leaguepoints,IF(l.class='R','RO',NULL)) AS char ) SEPARATOR ',') AS leaguesummary
FROM
(
SELECT 1 AS leaguescorecount, team, race, class, MAX(leaguepoints) AS leaguepoints, e2r.p2p_from as event
FROM wp_bhaa_teamresult trr
join wp_p2p e2r on (e2r.p2p_type='event_to_race' and e2r.p2p_to=trr.race)
WHERE class <> 'W'
GROUP BY team,race
) l
GROUP BY l.team
ORDER BY leaguepoints DESC
)t1, (SELECT @rownum:=0) t2;

END$$

DELIMITER ;
