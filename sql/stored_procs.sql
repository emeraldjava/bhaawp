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

-- 
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
-- update meta field
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
        scoringset int NULL)    ;

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
                       JOIN
                         wp_usermeta gender
                       ON (gender.user_id = r.runner AND gender.meta_key = 'bhaa_runner_gender')
              WHERE    r.race = _race AND COALESCE(r.standard, 0) > 0
              GROUP BY r.standard, gender.meta_value) x
      SET    ss.standardcount = x.standardcount 
      WHERE  ss.standard = x.standard AND ss.gender = x.gender ;

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

      UPDATE wp_bhaa_raceresult,
               scoringstandardsets
             JOIN
               wp_usermeta gender
             ON (gender.user_id = wp_bhaa_raceresult.runner AND gender.meta_key = 'bhaa_runner_gender')
      SET    wp_bhaa_raceresult.standardscoringset = scoringstandardsets.scoringset
      WHERE  scoringstandardsets.standard = wp_bhaa_raceresult.standard AND gender.meta_value = scoringstandardsets.gender;
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

      SELECT * FROM scoringstandardsets;

      UPDATE wp_bhaa_raceresult, scoringstandardsets
      SET    wp_bhaa_raceresult.standardscoringset = scoringstandardsets.scoringset
      WHERE  scoringstandardsets.standard = wp_bhaa_raceresult.standard and wp_bhaa_raceresult.race = _race;
    END IF;

    DROP TABLE scoringstandardsets;
END$$

-- updateRacePoints
DROP PROCEDURE IF EXISTS `updateRacePoints`$$
CREATE PROCEDURE `updateRacePoints`(_raceId INT, _gender ENUM('M','W'))
BEGIN
    DECLARE _standard INT DEFAULT 30;
  	WHILE _standard > 0 DO
      UPDATE wp_bhaa_raceresult rr,
      (
        SELECT
        r_outer.race,
        r_outer.runner,
        r_outer.standardposition,
        10.1 - (r_outer.standardposition/10.0) as standardpoints
        FROM
          (
          SELECT
          r1.race,
          r1.runner,
          @rownum:=@rownum+1 AS standardposition
          FROM
          wp_bhaa_raceresult r1, wp_users ru, (SELECT @rownum:=0) r2
          WHERE
          r1.runner=ru.id
          AND (select meta_value from wp_usermeta gender where gender.user_id = ru.id AND gender.meta_key = 'bhaa_runner_gender') = 
          	COALESCE(_gender, 
          		(select meta_value from wp_usermeta gender where gender.user_id = ru.id AND gender.meta_key = 'bhaa_runner_gender'))
          AND r1.race = _raceId AND r1.standard=_standard order by r1.position asc
          ) r_outer
        ) r_outer_outer
      SET rr.leaguepoints = r_outer_outer.standardpoints
      WHERE rr.runner = r_outer_outer.runner AND rr.race = r_outer_outer.race;
      SET _standard = _standard - 1;
   END WHILE;
END$$

-- SELECT user_nicename FROM wp_users WHERE id = _runner
-- select meta_value from wp_usermeta gender where gender.user_id = r.runner AND gender.meta_key = 'bhaa_runner_gender'
                       
DELIMITER ;
