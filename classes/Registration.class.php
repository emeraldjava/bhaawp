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
		$event = $eventModel->getNextEvent();
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
	
	function listRegisteredRunners()
	{
		$event = new EventModel($event->id);
		return $event->listRegisteredRunners();
	}
	
	/**
	 * Export the csv file for racetec
	 */
	function export()
	{
		
	}
}
?>