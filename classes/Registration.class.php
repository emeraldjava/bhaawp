<?php
/**
 * handles the raceday registration application
 * 
 * User Search
 * - http://www.blackbam.at/blackbams-blog/2011/06/27/wordpress-improved-user-search-first-name-last-name-email-in-backend/
 * - http://plugins.svn.wordpress.org/improved-user-search-in-backend/tags/1.2.3/improved-user-search-in-backend.php
 * 
 * http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
 * http://www.andrewmpeters.com/blog/how-to-make-jquery-ajax-json-requests-in-wordpress/
 * http://pippinsplugins.com/post-data-with-ajax-in-wordpress-pugins/
 * http://stackoverflow.com/questions/1960240/jquery-ajax-submit-form
 */
class Registration
{
	private $event;
	
	function __construct()
	{
		$eventModel = new EventModel();
		$this->event = $eventModel->getNextEvent();
	}
	
	function getEvent()
	{
		$event = new EventModel();
		return $event->getNextEvent();
	}
	
	function getNextRaces()
	{
		$event = new EventModel();
		return $event->getNextRaces();
	}
	
	function registerRunner($race,$runner,$racenumber)
	{
		$raceResult = new RaceResult($race);
		$raceResult->registerRunner($runner,$racenumber);
	}
	
	function addNewMember()
	{
		
	}

	/**
	 * Return the list of registered runners
	 */
	function listRegisteredRunners()
	{
		error_log($this->event);
		$event = new EventModel($this->event->post_id);
		return $event->listRegisteredRunners();
	}
	
	/**
	 * Export the csv file for racetec
	 */
	function export()
	{
		$event = new EventModel($this->event->post_id);
		$runners = $event->listRegisteredRunners();

		$output = "";
		$columns = $runners[0];
		foreach ($columns as $column => $value) {
			$output = stripslashes($output.$column.",");
		}
		$output = $output."\n";

		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: ".strlen($output));
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=".$this->event->event_slug.".csv");
		echo $output;
		exit;
		
	}
}
?>