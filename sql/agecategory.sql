
-- pull from bhaa to wp
select * from bhaaie_members.agecategory;

DROP TABLE IF EXISTS `wp_bhaa_agecategory`;
CREATE TABLE IF NOT EXISTS `wp_bhaa_agecategory` (
  `category` varchar(4) DEFAULT NULL,
  `gender` enum('M','W') DEFAULT 'M',
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  PRIMARY KEY (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

select * from wp_bhaa_agecategory;

insert into wp_bhaa_agecategory select category,gender,min,max from bhaaie_members.agecategory;
