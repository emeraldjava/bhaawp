
-- pull from bhaa to wp
select * from bhaaie_members.agecategory;

DROP TABLE IF EXISTS `wp_bhaa_agecategory`;
CREATE TABLE IF NOT EXISTS `wp_bhaa_agecategory` (
  `category` varchar(6) DEFAULT NULL,
  `gender` enum('M','W') DEFAULT 'M',
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  PRIMARY KEY (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

select * from wp_bhaa_agecategory;
delete from wp_bhaa_agecategory;

INSERT INTO `wp_bhaa_agecategory` (`category`, `min`, `max`) VALUES
('Senior', 18, 34),
('35', 35, 39),
('40', 40, 44),
('45', 45, 49),
('50', 50, 54),
('55', 55, 59),
('60', 60, 64),
('65', 65, 69),
('70', 70, 74),
('75', 75, 79),
('80', 80, 84),
('85', 85, 120),
('Junior', 0, 17);

insert into wp_bhaa_agecategory select category,gender,min,max from bhaaie_members.agecategory;
alter table wp_bhaa_agecategory DROP gender;

-- 35 mens
update wp_bhaa_agecategory set max=34 where category='SM';
insert into wp_bhaa_agecategory VALUES('M35','M',35,39);

SELECT DISTINCT(category) FROM wp_bhaa_raceresult;
UPDATE wp_bhaa_raceresult SET category = 'S' WHERE category = 'Senior';
UPDATE wp_bhaa_raceresult SET category = 'S' WHERE category IS NULL;

-- r7713 and race=3854
SELECT runner.id,eventdate,gender.meta_value,dob.meta_value,
getAgeCategory(dob.meta_value,eventdate,gender.meta_value) as age,
CONCAT(getAgeCategory(dob.meta_value,eventdate,gender.meta_value),gender.meta_value) as ageCat
FROM wp_bhaa_race_detail 
LEFT JOIN wp_users runner ON runner.id=7713 
LEFT JOIN wp_usermeta gender ON (gender.user_id=runner.id and gender.meta_key='bhaa_runner_gender') 
LEFT JOIN wp_usermeta dob ON (dob.user_id=runner.id and dob.meta_key='bhaa_runner_dateofbirth') 
WHERE race=3854 LIMIT 1
