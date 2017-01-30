<?php
class Raceday_DayMember_Form extends Raceday_Form {

	function __construct() {
		parent::__construct();
		$this->money_drop_down
			->add_option(5,'25e New')
			->add_option(4,'20e Day');
	}

	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		$form->set_attribute('autocomplete','off');
		$args = func_get_args();
		call_user_func_array(array($this, 'dayMemberForm'), $args);
	}

	private function dayMemberForm(WP_Form $form) {
		$eventFieldSet = WP_Form_Element::create('fieldset')
			->set_name('raceFieldSet')
			->set_label('<b>Event Details</b>')
			->set_classes(array('col-md-5'));
		$runnerFieldSet = WP_Form_Element::create('fieldset')
			->set_name('runnerFieldSet')
			->set_label('<b>Runner Details</b>')
			->set_classes(array('col-md-5'));
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_classes(array('btn btn-info'))
			->set_value('Register Runner')
			->set_label('Register Runner');

		$eventFieldSet->add_element($this->racenumber);
		$eventFieldSet->add_element($this->race_drop_down);
		$eventFieldSet->add_element($this->money_drop_down);
		$eventFieldSet->add_element($submit);

		$firstname = WP_Form_Element::create('text')
			->set_name('bhaa_firstname')
			->set_label('First Name')
			->set_id('bhaa_firstname')
			->set_classes(array('form-control'));
		$lastname = WP_Form_Element::create('text')
			->set_name('bhaa_lastname')
			->set_label('Last Name')
			->set_id('bhaa_lastname')
			->set_classes(array('form-control'));
		$dateofbirth = WP_Form_Element::create('text')
			->set_name('bhaa_dateofbirth')
			->set_label('Date of Birth - DD-MM-YYYY')
			->set_id('bhaa_dateofbirth')
			->set_classes(array('datepicker form-control'));
		$runner = WP_Form_Element::create('hidden')
			->set_name('bhaa_runner')
			->set_id('bhaa_runner')
			->set_value(0);

		$runnerFieldSet->add_element($firstname);
		$runnerFieldSet->add_element($lastname);
		$runnerFieldSet->add_element($dateofbirth);
		$runnerFieldSet->add_element($this->gender_drop_down);

		$form->add_element($eventFieldSet)
			->add_element($runnerFieldSet)
			->add_validator(array($this,'bhaa_day_validation_callback'))
			->add_processor(array($this,'bhaa_day_processing_callback'));
	}

	public function bhaa_day_validation_callback(
		WP_Form_Submission $submission, WP_Form $form ) {

		// bhaa_firstname
		$firstname = $submission->get_value('bhaa_firstname');
		if(!isset($firstname)||$firstname=='')
			$submission->add_error('bhaa_firstname', 'First name is required!');

		// bhaa_lastname
		$bhaa_lastname = $submission->get_value('bhaa_lastname');
		if(!isset($bhaa_lastname)||$bhaa_lastname=='')
			$submission->add_error('bhaa_lastname', 'Surname name is required!');

		// date of birth format
		// http://stackoverflow.com/questions/19773418/regex-to-validate-date-in-php-using-format-as-yyyy-mm-dd
		$dob = $submission->get_value('bhaa_dateofbirth');
		$date_regex = '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/';
		if (!preg_match($date_regex,$dob))
			$submission->add_error('bhaa_dateofbirth', 'Date of Birth '.$dob.' MUST be DD-MM-YYYY format!');

		// gender
		$gender = $submission->get_value('bhaa_gender');
		if(!isset($gender))
			$submission->add_error('bhaa_gender', 'Enter the runners gender!');

		//error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		parent::bhaa_common_validation($submission,$form);
	}

	public function bhaa_day_processing_callback(
		WP_Form_Submission $submission, WP_Form $form ) {

		$race = $submission->get_value('bhaa_race');
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		$standard = $submission->get_value('bhaa_standard');
		$money = $submission->get_value('bhaa_money');

		if(!isset($runner)) {

			$a = explode('-',$submission->get_value('bhaa_dateofbirth'));
			$my_new_date = $a[2].'-'.$a[1].'-'.$a[0];

			$runner = Runner_Manager::get_instance()->addNewMember(
				ucfirst($submission->get_value('bhaa_firstname')),
				ucfirst($submission->get_value('bhaa_lastname')),
				$submission->get_value('bhaa_gender'),
				$my_new_date);
			error_log("created new runner ".$runner);
		}

		error_log(sprintf('bhaa_day_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		Raceday::get_instance()->registerRunner($race, $runner, $racenumber, 30, $money);

		// redirect the user after the form is submitted successfully
		$submission->set_redirect('/raceday-list');
	}
}
?>
