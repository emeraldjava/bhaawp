
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
