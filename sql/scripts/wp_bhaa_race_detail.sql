
-- show the 5 race types with track and summary
CREATE TABLE wp_bhaa_race_detail (
	id int(11) auto_increment primary key,
	event int(11) NOT NULL,
	eventname varchar(10) NOT NULL,
	race int(11) NOT NULL,
	type varchar(1) NOT NULL,
	distance double
);
