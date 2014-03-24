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
	private $eventModel = null;
	private $event = null;
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	function __construct() {
		$this->eventModel = new EventModel();
		$this->event = $this->eventModel->getNextEvent();
	}
	
	function bhaa_register_forms() {
		$registrationForm = new Raceday_Registration_Form();
		wp_register_form('registerform',array($registrationForm,'build_form'));
		
		$dayMemberForm = new Raceday_DayMember_Form();
		wp_register_form('daymemberform',array($dayMemberForm,'build_form'));
	}
	
	function handlePage($pagename) {
		switch($pagename){
			case 'raceday-register':
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
			case 'raceday-admin':
				include_once BHAA_PLUGIN_DIR.'/public/views/raceday-admin.php';
				break;
			case 'raceday-cash':
				$this->cash();
				break;
			case 'raceday-prereg':
				include_once BHAA_PLUGIN_DIR.'/public/views/raceday-prereg.php';
				break;
			// all
			// ABSPATH.'wp-content/bhaa_all_members.html';
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
		return $this->eventModel->getNextRaces();
	}
	
	function registerRunner($race,$runner,$racenumber,$standard,$money) {
		$raceResult = new RaceResult($race);
		return $raceResult->registerRunner($runner,$racenumber,$standard,$money);
	}
	
	function preRegisterRunner($race,$runner,$racenumber,$money) {
		$raceResult = new RaceResult($race);
		return $raceResult->preRegisterRunner($runner,$racenumber,$money);
	}
	
	/**
	 * Return the list of registered runners
	 */
	function listRegisteredRunners($limit=0,$class='RACE_REG',$order='wp_bhaa_raceresult.racetime desc, wp_bhaa_raceresult.id desc') {
		//$special_query_results = get_transient( 'bhaa_registered_runners' );
		//if ( empty( $special_query_results ) ){
		//	$special_query_results = $this->eventModel->listRegisteredRunners($limit,$class,$order);
		//	set_transient( 'bhaa_registered_runners', $special_query_results, MINUTE_IN_SECONDS );
		//} 
		//return $special_query_results;
		return $this->eventModel->listRegisteredRunners($limit,$class,$order);
	}
	
	/**
	 * Return the list of all pre-registered runners
	 */
	function listPreRegisteredRunners() {
		//$event = new EventModel($this->event->post_id);
		return $this->eventModel->listRegisteredRunners(0,RaceResult::PRE_REG);
	}
	
	function deleteRunner($runner,$race) {
		$raceResult = new RaceResult($race);
		return $raceResult->deleteRunner($runner);
	}
	
	function getRegistrationTypes($race) {
		$raceResult = new RaceResult($race);
		return $raceResult->getRegistrationTypes();
	}
	
	/**
	 * Export the csv file for racetec
	 */
	function export() {
		error_log("export");
		//$event = new EventModel(3032);//$this->event->post_id);
		$runners = $this->eventModel->listRegisteredRunners();

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
	//	ob_end_clean();
		
		//http://faisonz.com/creating-a-wordpress-plugin-to-export-mysql-data-as-csv/
//		$fp = fopen('php://output', 'w');
	//	fputcsv($fp, $output);
		//fclose($fp);
		// This function removes all content from the output buffer
		//ob_end_clean();
		error_log("exported file");
		echo $output;
		//exit;		
	}
}
?>