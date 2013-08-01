<?php
/**
 * handles the raceday registration application
 */
class Raceday
{
	const BHAA_RACEDAY_FORM = 'bhaa_raceday_form';
	const BHAA_RACEDAY_FORM_REGISTER = 'bhaa_raceday_form_register';
	const BHAA_RACEDAY_FORM_NEWMEMBER = 'bhaa_raceday_form_newmember';
	const BHAA_RACEDAY_FORM_PREREG = 'bhaa_raceday_form_prereg';
	
	private $event;
	
	function __construct() {
		$eventModel = new EventModel();
		$this->event = $eventModel->getNextEvent();
		// https://github.com/jbrinley/wp-forms
		add_action( 'wp_forms_register',array($this,'register_my_form'), 10, 0 );
	}
	
	function register_my_form() {
		error_log('register_my_form');
		wp_register_form( 'my-unique-form-id', array($this,'my_form_building_callback') );
	}
	
	function my_form_building_callback( $form ) {
		error_log('my_form_building_callback');
		$form->add_element(
				WP_Form_Element::create('text')
				->set_name('first_name')
				->set_label('First Name')
			)->add_element(
				WP_form_Element::create('text')
				->set_name('last_name')
				->set_label('Last Name')
			)->add_element(
				WP_Form_Element::create('submit')
				->set_name('submit')
				->set_label('WP-FORM')
			);		
		$form->add_validator( array($this,'my_validation_callback'), 10 );	
	}
	
	function my_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		error_log('my_validation_callback');
		if ( $submission->get_value('first_name') != 'Jonathan' ) {
			$submission->add_error('first_name', 'Your name should be Jonathan');
		}
	}
	
	function getEvent() {
		return $this->event;
	}
	
	function getNextRaces()	{
		$event = new EventModel();
		return $event->getNextRaces();
	}
	
	function registerRunner($race,$runner,$racenumber,$standard,$money)
	{
		$raceResult = new RaceResult($race);
		return $raceResult->registerRunner($runner,$racenumber,$standard,$money);
	}
	
	function preRegisterRunner($race,$runner,$racenumber,$money)
	{
		$raceResult = new RaceResult($race);
		return $raceResult->preRegisterRunner($runner,$racenumber,$money);
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
	function listRegisteredRunners($limit=0,$class='RACE_REG',$order='wp_bhaa_raceresult.racetime desc, wp_bhaa_raceresult.id desc') {
		$event = new EventModel($this->event->post_id);
		return $event->listRegisteredRunners($limit,$class,$order);
	}
	
	/**
	 * Return the list of all pre-registered runners
	 */
	function listPreRegisteredRunners() {
		$event = new EventModel($this->event->post_id);
		return $event->listRegisteredRunners(0,RaceResult::PRE_REG);
	}
	
	function deleteRunner($runner,$race)
	{
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