-- bhaa stored proces

-- http://www.coderrants.com/wordpress-and-stored-procedures/
-- http://wordpress.org/support/topic/how-to-call-stored-procedure-from-plugin

SET GLOBAL log_bin_trust_function_creators = 1;

DELIMITER $$

-- only functions return values
DROP FUNCTION IF EXISTS `getUsername`$$
CREATE DEFINER=`bhaaie_wp`@`localhost` FUNCTION `getUsername`(_runner INT) RETURNS int(11)
BEGIN
DECLARE _result int;
SET _result  = (SELECT user_nicename FROM wp_users WHERE id = _runner);
RETURN _result;
END $$

DROP FUNCTION IF EXISTS `getRaceDistanceKm`$$
CREATE DEFINER=`bhaaie_wp`@`localhost` FUNCTION `getRaceDistanceKm`(_race INT) RETURNS double
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
CREATE DEFINER=`bhaaie_wp`@`localhost` FUNCTION `getStandard`(_raceTime TIME, _distanceKm DOUBLE) RETURNS int(11)
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
CREATE DEFINER=`bhaaie_wp`@`localhost` FUNCTION `getAgeCategory`(_birthDate DATE, _currentDate DATE, _gender ENUM('M','W')) RETURNS varchar(4) CHARSET utf8
BEGIN
DECLARE _age INT(11);
SET _age = (YEAR(_currentDate)-YEAR(_birthDate)) - (RIGHT(_currentDate,5)<RIGHT(_birthDate,5));
RETURN (SELECT category FROM wp_bhaa_agecategory WHERE (_age between min and max) and gender=_gender);
END$$

-- 
DROP PROCEDURE IF EXISTS `updatePositionInStandard`$$
CREATE DEFINER=`bhaaie_wp`@`localhost` PROCEDURE `updatePositionInStandard`(_raceId INT(11))
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
CREATE DEFINER=`bhaaie_wp`@`localhost` PROCEDURE `updatePositionInAgeCategory`(_raceId INT(11))
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
CREATE DEFINER=`bhaaie_wp`@`localhost` PROCEDURE `updatePostRaceStandard`(_raceId INT)
BEGIN

UPDATE wp_bhaa_raceresult rr_outer,
(
SELECT rr.race,rr.runner,rr.racetime, rr.standard, getStandard(rr.racetime, ra.distancekm) as poststandard
FROM wp_bhaa_raceresult rr ,runner ru
JOIN wp_post post on (posts.post_type='race' and post.ID=wp_bhaa_raceresult.race)
WHERE rr.race = ra.id AND rr.runner=ru.id AND ra.id = _raceId AND rr.class='RAN'

) t
SET rr_outer.poststandard =
CASE
  WHEN t.standard IS NULL
	  THEN t.poststandard
  WHEN t.standard  < t.poststandard
	  THEN t.standard  + 1
  WHEN t.standard  > t.poststandard
	  THEN t.standard  - 1
  WHEN t.standard  = t.poststandard
    THEN t.standard
END
WHERE rr_outer.race = t.race AND rr_outer.runner=t.runner;


UPDATE raceresult, runner
SET runner.standard = raceresult.poststandard
WHERE raceresult.runner = runner.id
AND raceresult.race = _raceId
AND COALESCE(runner.standard,0) <> raceresult.poststandard
AND runner.status='M';
END$$

DELIMITER ;
