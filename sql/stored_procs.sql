-- bhaa stored proces

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

DELIMITER ;
