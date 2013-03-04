-- bhaa stored proces

-- http://www.coderrants.com/wordpress-and-stored-procedures/
-- http://wordpress.org/support/topic/how-to-call-stored-procedure-from-plugin

-- SET GLOBAL log_bin_trust_function_creators = 1;

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

	declare _raceType enum('m','w','c');
	declare _scoringthreshold int;
	declare _currentstandard int;
	declare _runningtotal int;
	declare _runningtotalM int;
	declare _runningtotalW int;
	declare _scoringset int;
	declare _scoringsetM int;
	declare _scoringsetW int;
	
	-- TODO look this up from the league
	set _scoringthreshold = 4; 
    set _currentstandard = 30;
	set _runningtotal = 0;
	set _runningtotalM = 0;
	set _runningtotalW = 0;
	set _scoringset = 1;
	set _scoringsetM = 1;
	set _scoringsetW = 1;

    update wp_bhaa_raceresult set standardscoringset = null where race = _race;
    
    create temporary table if not exists scoringstandardsets(
        standard int, 
        gender enum('M','W') null, 
        standardcount int null, 
        scoringset int null);

    set _raceType = (select meta_value from wp_postmeta where post_id=_race and meta_key='bhaa_race_type');
    if (_raceType = 'C') then
		
		insert into scoringstandardsets(standard,standardcount,scoringset,gender)
			select standard,0,0,'W' from wp_bhaa_standard
		union
			select standard,0,0,'M' from wp_bhaa_standard;
		
        update scoringstandardsets ss,
        (
            select r.standard, gender.meta_value as gender, count(r.standard) as standardcount
            from wp_bhaa_raceresult r
            join wp_usermeta gender on (gender.user_id=r.runner and gender.meta_key='bhaa_runner_gender')
            where r.race = _race
            group by r.standard, gender.meta_value
        ) x
        set ss.standardcount = x.standardcount
        where ss.standard = x.standard and ss.gender = x.gender;
        
        while (_currentstandard > 0) do
			
            set _runningtotalM =
                (select sum(standardcount)
                from scoringstandardsets
                where standard >= _currentstandard and scoringset =0 and gender='M');

            set _runningtotalW =
                (select sum(standardcount)
                from scoringstandardsets
                where standard >= _currentstandard and scoringset =0 and gender='W');
                
            if (_runningtotalM >= _scoringthreshold) then
                
                update scoringstandardsets 
                set scoringset= _scoringsetM 
                where standard >= _currentstandard 
                and scoringset=0 
                and gender='M';
                
                set _scoringsetM = _scoringsetM + 1;
                set _runningtotalM = 0;
            end if;
            
            if (_runningtotalW >= _scoringthreshold) then
                
                update scoringstandardsets 
                set scoringset= _scoringsetW 
                where standard >= _currentstandard 
                and scoringset=0 
                and gender='W';
                
                set _scoringsetW = _scoringsetW + 1;
                set _runningtotalW = 0;
                
            end if;
            
            set _currentstandard = _currentstandard-1;
        end while;
    
		update scoringstandardsets set scoringset= _scoringsetW where scoringset=0 and gender='W';    
	    update scoringstandardsets set scoringset= _scoringsetM where scoringset=0 and gender='M';    

		update wp_bhaa_raceresult, scoringstandardsets
		join wp_usermeta gender on (gender.user_id=wp_bhaa_raceresult.runner and gender.meta_key='bhaa_runner_gender')
		set wp_bhaa_raceresult.standardscoringset = scoringstandardsets.scoringset
		where scoringstandardsets.standard = wp_bhaa_raceresult.standard 
	    and gender.meta_value = scoringstandardsets.gender;
    
    else
		insert into scoringstandardsets(standard,standardcount,scoringset)
			select standard,0,0 from wp_bhaa_standard order by standard desc;
		
		update scoringstandardsets ss,
        (
            select r.standard, gender.meta_value as gender, count(r.standard) as standardcount
            from wp_bhaa_raceresult r
            join wp_usermeta gender on (gender.user_id=r.runner and gender.meta_key='bhaa_runner_gender')
            where r.race = _race
            group by r.standard, gender.meta_value
        ) x
        set ss.gender=x.gender, ss.standardcount = x.standardcount
        where ss.standard = x.standard;
        
        while (_currentstandard > 0) do
            set _runningtotal =
            (select sum(standardcount)
            from scoringstandardsets
            where standard >= _currentstandard and scoringstandardsets.scoringset =0);

            if (_runningtotal >= _scoringthreshold) then
                update wp_bhaa_raceresult set wp_bhaa_raceresult.standardscoringset=_scoringset 
                where standard >= _currentstandard and wp_bhaa_raceresult.standardscoringset=0;
                
                set _scoringset = _scoringset+1;
                set _runningtotal = 0;
            end if;

            set _currentstandard = _currentstandard-1;
        end while;
        
		update scoringstandardsets set scoringset= _scoringset where scoringset=0;

		update wp_bhaa_raceresult, scoringstandardsets
		set wp_bhaa_raceresult.standardscoringset = scoringstandardsets.scoringset
		where scoringstandardsets.standard = wp_bhaa_raceresult.standard;
		
    end if;

	drop table scoringstandardsets;
END$$

DELIMITER ;
