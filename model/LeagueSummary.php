<?php
class LeagueSummary implements Table
{
	function __construct()
	{}
	
	function getName()
	{
		global $wpdb;
		return $wpdb->prefix.'bhaa_leaguesummary';
	}
	
	function getCreateSQL()
	{
		return "
			league int(10) unsigned NOT NULL AUTO_INCREMENT,
			leaguetype enum('I','T') NOT NULL,
			leagueparticipant int(10) unsigned NOT NULL,
			leaguestandard int(10) unsigned NOT NULL,
			leaguedivision varchar(5) NOT NULL,
			leagueposition int(10) unsigned NOT NULL,
			leaguescorecount int(10) unsigned NOT NULL,
			leaguepoints double NOT NULL,
			PRIMARY KEY (leaguetype,leagueid,leagueparticipantid,leaguedivision) USING BTREE";
	}
	
	function getDivisions()
	{}

	// return a summary of the top x in each division
	function getLeageSummary($limit=10)
	{}
	
	// get the specific of a league division
	function getDivisionSummary($division) // limit - all or 10?
	{}
}
?>