<?php
class LeagueSummary extends BaseModel implements Table
{
	private $league;
	
	function __construct($league)
	{
		parent::__construct();
		$this->league=$league;
	}
	
	function getName()
	{
		global $wpdb;
		return $wpdb->prefix.'bhaa_leaguesummary';
	}
	
	function getCreateSQL()
	{
		return "
			league int(10) unsigned NOT NULL,
			leaguetype enum('I','T') NOT NULL,
			leagueparticipant int(10) unsigned NOT NULL,
			leaguestandard int(10) unsigned NOT NULL,
			leaguedivision varchar(5) NOT NULL,
			leagueposition int(10) unsigned NOT NULL,
			leaguescorecount int(10) unsigned NOT NULL,
			leaguepoints double NOT NULL,
			leaguesummary varchar(500),
			PRIMARY KEY (leaguetype, league, leagueparticipant, leaguedivision) USING BTREE";
	}
	
	function getDivisions()
	{}

	// return a summary of the top x in each division
	function getLeagueSummary($limit=10)
	{
		global $wpdb;
		$query = $wpdb->prepare('
			SELECT *,wp_users.display_name
			FROM wp_bhaa_leaguesummary
			left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
			WHERE leaguetype = "I"
			AND leagueposition <= %d
			AND league = %d
			order by league, leaguedivision, leagueposition',$limit,$this->league);
		error_log($query);
		$this->items = $wpdb->get_results($query);
		return $this->items;	
	}
	
	// get the specific of a league division
	function getDivisionSummary($division) // limit - all or 10?
	{
		$SQL = $this->wpdb->prepare('select * from wp_bhaa_leaguesummary
			left join wp_users on wp_users.id=wp_bhaa_leaguesummary.leagueparticipant 
			where league=%d and leaguedivision=%s',$this->league,$division);
		error_log($SQL);
		return $this->wpdb->get_results($SQL);
	}
}
?>