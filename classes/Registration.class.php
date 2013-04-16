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
	
	function registerRunner($race,$runner,$racenumber,$standard)
	{
		$raceResult = new RaceResult($race);
		return $raceResult->registerRunner($runner,$racenumber,$standard);
	}
	
	function preRegisterRunner($race,$runner,$racenumber)
	{
		$raceResult = new RaceResult($race);
		return $raceResult->preRegisterRunner($runner,$racenumber);
	}
	
	function addNewMember($firstname,$lastname,$gender,$dateofbirth,$email='')
	{
		// lookup create runner
		$runner = new Runner();
		$match = $runner->matchRunner($firstname,$lastname,$dateofbirth);
		if($match!=0)
		{
			$runner_id = $match;
			error_log('matched existing runner '.$runner_id);
		}
		else
		{
			$runner_id = $runner->createNewUser($firstname,$lastname,$email,$gender,$dateofbirth);
			error_log('created new runner '.$runner_id);
		}
		return $runner_id;
	}

	/**
	 * Return the list of registered runners
	 */
	function listRegisteredRunners($limit=0)
	{
		$event = new EventModel($this->event->post_id);
		return $event->listRegisteredRunners($limit);
	}
	
	/**
	 * Return the list of all pre-registered runners
	 */
	function listPreRegisteredRunners()
	{
		$event = new EventModel($this->event->post_id);
		return $event->listRegisteredRunners(0,RaceResult::PRE_REG);
	}
	
	function deleteRunner($runner,$race)
	{
		$raceResult = new RaceResult($race);
		return $raceResult->deleteRunner($runner);
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
		
		foreach ($runners as $rowArray) {
			foreach ($rowArray as $column => $value)
			{
				// string any comma's or the csv file is screwed.
				$value = str_replace(",","",$value);
				$value = html_entity_decode($value);
				 
				switch ($column) {
					case "runner":
						if($value=="DAY")
							$output =  stripslashes($output.",");
						else
							$output =  stripslashes($output.$value.",");
						break;
					case "teamid":
						if($value=="0")
							$output =  stripslashes($output.",");
						else
							$output =  stripslashes($output.$value.",");
						break;
					case "companyid":
						if($value=="0")
							$output =  stripslashes($output.",");
						else
							$output =  stripslashes($output.$value.",");
						break;
					default:
						$output =  stripslashes($output.$value.",");
				}
			}
			$output = $output."\n";
		}
		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: ".strlen($output));
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=".$this->event->event_slug.".csv");
		echo $output;
		exit;		
	}
}
?>