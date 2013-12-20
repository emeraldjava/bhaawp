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
	
	private function __construct() {
		$eventModel = new EventModel();
		$this->event = $eventModel->getNextEvent();
		
		// https://github.com/jbrinley/wp-forms
		//add_action('wp_forms_register',array($this,'register_my_form'), 10, 0 );
		
		// filters
		//add_filter('wp_form_default_decorators',array($this,'filter_button_decorators'), 10, 2 );
		//  filter wp_form_htmltag_default
	}
	
	function handlePage($pagename){
		error_log('handlePage('.$pagename.')');
		switch($pagename){
			case 'raceday-register':
				$this->registerForm();
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
			// raceday-cash
			// raceday-admin
			// prereg
			// all
			default :
				include_once BHAA_PLUGIN_DIR.'/public/views/raceday.php';
		}
	}
	
	private function registerForm() {
		wp_register_form( 'bhaa-register-form', array($this,'bhaaRegisterForm') );
	} 
	
	private function listRunners($size=NULL) {
		$racetec = $this->listRegisteredRunners($size);
		//echo $racetec;
		$_REQUEST['racetec']=$racetec;
		include_once BHAA_PLUGIN_DIR.'/public/views/raceday-list.php';
	}
	
	
	//	function register_my_form() {
	//		error_log('register_my_form');
	//		wp_register_form( 'my-unique-form-id', array($this,'my_form_building_callback') );
	//	}
	
	/**
	 *
	 * http://jsfiddle.net/kY5LL/12/
	 * @param unknown $form
	 */
	function bhaaRegisterForm( $form ) {
		error_log('bhaaRegisterForm');
	
		$fieldSet = WP_Form_Element::create('fieldset')->set_name('fieldset')->set_label('fieldset');
		
	
		$firstname = WP_Form_Element::create('text')->set_name('xfirstname')->set_label('First Name')->set_id('firstname');
		//$firstname->set_view(new WP_Form_View_Input());
		//$firstname->add_decorator('WP_Form_Decorator_Label', array());
		//$firstname->add_decorator('WP_Form_Decorator_Description', array());
		//$firstname->add_decorator('WP_Form_Decorator_HtmlTag', array('tag' => 'div', 'attributes' => array( 'class' => 'control-group' )));
		
		$lastname = WP_Form_Element::create('text')->set_name('xlastname')->set_label('Last Name')->set_id('lastname');
		//$lastname->set_view(new WP_Form_View_Input());
		//$lastname->add_decorator('WP_Form_Decorator_Label', array('position' => WP_Form_Decorator::POSITION_BEFORE));
		//$lastname->add_decorator('WP_Form_Decorator_Description', array());
		//$lastname->add_decorator('WP_Form_Decorator_HtmlTag', array('tag' => 'div', 'attributes' => array( 'class' => 'control-group' )));
		
		//$fieldSet->add_element($firstname);
		//$fieldSet->add_element($lastname);
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_label('WP-FORM');
		
		$form->add_element( WP_Form_Element::create('number')
                ->set_name('number')->set_id('number')
                ->set_label('Race Number'));
		$form->add_element( WP_Form_Element::create('number')
				->set_name('runner')->set_id('runner')
				->set_label('BHAA ID'));
		$form->add_element($firstname);
		$form->add_element($lastname);
		//$form->add_element($fieldSet);
		$form->add_element($submit);
		$form->add_class('form-example');
		
		$form->add_validator( array($this,'my_validation_callback'), 10 );
		$form->add_processor( array($this,'my_processing_callback'), 10 );
	} 
	
	function filter_button_views( $decorators, $element ) {
	 if ( $element->type == 'text' ) {
	 		$decorators = array(
	 			'WP_Form_Decorator_HtmlTag' => array('tag' => 'div',
	 			'attributes' => array( 'class' => 'control-group' )),
	 	);
	 }
	 return $decorators;
	}
	
	function my_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		$first_name = $submission->get_value('first_name');
		// do something with $first_name
		error_log('firstname '.$first_name);
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('');//home_url('aPage'));
	}

	function my_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		error_log('my_validation_callback');
		//if ( $submission->get_value('first_name') != 'Jonathan' ) {
		//	$submission->add_error('first_name', 'Your name should be Jonathan');
		//}
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