-- bhaa stored proces

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

DROP PROCEDURE IF EXISTS `doSP`$$
CREATE DEFINER=`bhaaie_wp`@`localhost` PROCEDURE `doSP`(_runner INT)
BEGIN
DECLARE _result int;
SELECT user_nicename FROM wp_users WHERE id = _runner;
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

DELIMITER ;
