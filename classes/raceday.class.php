<?php
/**
 * handles the raceday registration application
 * 
 * User Search
 * - http://www.blackbam.at/blackbams-blog/2011/06/27/wordpress-improved-user-search-first-name-last-name-email-in-backend/
 * - http://plugins.svn.wordpress.org/improved-user-search-in-backend/tags/1.2.3/improved-user-search-in-backend.php
 */
class RaceDay
{
	function RaceDay()
	{}
	
	function race_day()
	{
		if(is_user_logged_in()||current_user_can('manage_options'))
		{
			echo "This will be the BHAA race day race number allocation app.";
		}
		else 
		{
			echo '<h2>You must be logged in with specific permissions to access this page</h2>';
		}
	}
}
?>