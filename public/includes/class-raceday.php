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
class Raceday
{
	private $event;
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	function __construct() {
		$eventModel = new EventModel();
		$this->event = $eventModel->getNextEvent();
		//error_log(print_r($this->event,true));
	}
	
	function bhaa_register_forms() {
		$registrationForm = new Raceday_Registration_Form();
		$val = wp_register_form('registerform', array($registrationForm,'build_form'));
	}
	
	function handlePage($pagename) {
		error_log('handlePage('.$pagename.')');
		switch($pagename){
			case 'raceday-register':
				//$this->registerForm();
				include_once BHAA_PLUGIN_DIR.'/public/views/raceday-register.php';
				break;
			case 'raceday-newmember':
				include_once BHAA_PLUGIN_DIR.'/public/views/raceday-newmember.php';
				break;
			case 'raceday-latest':
				$this->listRunners(10);
				break;
			case 'raceday-list':
				$this->listRunners();			
				break;
			case 'raceday-export':
				$this->export();
				break;
			case 'raceday-cash':
				$this->cash();
				break;
			// raceday-admin
			// prereg
			// all
			default :
				include_once BHAA_PLUGIN_DIR.'/public/views/raceday.php';
		}
	}
		
	private function listRunners($size=NULL) {
		$racetec = $this->listRegisteredRunners($size);
		//echo $racetec;
		$_REQUEST['racetec']=$racetec;
		include_once BHAA_PLUGIN_DIR.'/public/views/raceday-list.php';
	}
	
	private function cash() {
		$event = $this->getEvent();
		$runnerCount = $this->getRegistrationTypes($event->race);
		$registeredRunners = $this->listRegisteredRunners(
				0,'RACE_REG','wp_bhaa_raceresult.standardscoringset asc, wp_bhaa_raceresult.id desc');
		$_REQUEST['event']=$event;
		$_REQUEST['runnerCount']=$runnerCount;
		$_REQUEST['registeredRunners']=$registeredRunners;
		include_once BHAA_PLUGIN_DIR.'/public/views/raceday-cash.php';
	}
	
	function getEvent() {
		return $this->event;
	}
	
	function getNextRaces()	{
		$event = new EventModel();
		return $event->getNextRaces();
	}
	
	function registerRunner($race,$runner,$racenumber,$standard,$money) {
		$raceResult = new RaceResult($race);
		return $raceResult->registerRunner($runner,$racenumber,$standard,$money);
	}
	
	function preRegisterRunner($race,$runner,$racenumber,$money) {
		$raceResult = new RaceResult($race);
		return $raceResult->preRegisterRunner($runner,$racenumber,$money);
	}
	
	function addNewMember($firstname,$lastname,$gender,$dateofbirth,$email='') {
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
	function listRegisteredRunners($limit=0,$class='RACE_REG',$order='wp_bhaa_raceresult.racetime desc, wp_bhaa_raceresult.id desc') {
		$event = new EventModel(3030);//$this->event->post_id);
		return $event->listRegisteredRunners($limit,$class,$order);
	}
	
	/**
	 * Return the list of all pre-registered runners
	 */
	function listPreRegisteredRunners() {
		$event = new EventModel($this->event->post_id);
		return $event->listRegisteredRunners(0,RaceResult::PRE_REG);
	}
	
	function deleteRunner($runner,$race) {
		$raceResult = new RaceResult($race);
		return $raceResult->deleteRunner($runner);
	}
	
	function getRegistrationTypes($race) {
		$raceResult = new RaceResult($race);
		return $raceResult->getRegistrationTypes($runner);
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
					case "gender":
						if($value=="W")
							$output =  stripslashes($output."F,");
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