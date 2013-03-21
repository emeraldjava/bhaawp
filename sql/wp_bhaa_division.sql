-- wp_bhaa_division
DROP TABLE IF EXISTS `wp_bhaa_division`;
CREATE TABLE IF NOT EXISTS `wp_bhaa_division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `code` varchar(2) DEFAULT NULL,
  `gender` enum('M','W','T') DEFAULT 'M',
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `type` enum('I','T') DEFAULT 'I',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14;

--
-- Dumping data for table `wp_bhaa_division`
--
INSERT INTO `wp_bhaa_division` (`id`, `name`, `code`, `gender`, `min`, `max`, `type`) VALUES
(1, 'Men Division A', 'A', 'M', 1, 7, 'I'),
(2, 'Men Division B', 'B', 'M', 8, 10, 'I'),
(3, 'Men Division C', 'C', 'M', 11, 13, 'I'),
(4, 'Men Division D', 'D', 'M', 14, 16, 'I'),
(5, 'Men Division E', 'E', 'M', 17, 21, 'I'),
(6, 'Men Division F', 'F', 'M', 22, 30, 'I'),
(7, 'Women Division A', 'L1', 'W', 1, 16, 'I'),
(8, 'Women Division B', 'L2', 'W', 17, 30, 'I'),
(9, 'Mens Team League A', 'A', 'M', 1, 30, 'T'),
(10, 'Mens Team League B', 'B', 'M', 31, 38, 'T'),
(11, 'Mens Team League C', 'C', 'M', 39, 46, 'T'),
(12, 'Mens Team League D', 'D', 'M', 47, 90, 'T'),
(13, 'Womens Team League', 'W', 'W', 1, 90, 'T');