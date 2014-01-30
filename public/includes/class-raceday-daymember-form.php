<?php 
class Raceday_DayMember_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		$args = func_get_args();
		call_user_func_array(array($this, 'dayMemberForm'), $args);
	}
	
	/**
	 *
	 */
	private function dayMemberForm(WP_Form $form) {
		
		$eventFieldSet = WP_Form_Element::create('fieldset')->
			set_name('raceFieldSet')->set_label('Event Details')->set_classes(array('col-md-6'));
		$runnerFieldSet = WP_Form_Element::create('fieldset')->
			set_name('runnerFieldSet')->set_label('Runner Details')->set_classes(array('col-md-6'));
		
		// race day field set
		$racenumber = WP_Form_Element::create('number')
			->set_name('bhaa_racenumber')->set_id('bhaa_racenumber')
			->set_label('Race Number')->set_classes(array('form-control'));

		$race_drop_down = WP_Form_Element::create('radios')->set_name('bhaa_race')->set_label('Race');
		$race_drop_down
			->add_option(3256,'5Mile Men')
			->add_option(3255,'2M Women');
			
		$money_drop_down = WP_Form_Element::create('radios')->set_name('bhaa_money')->set_label('Money');
		$money_drop_down
			->add_option(4,'15e Day Member')
			->add_option(5,'25e New Member');
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_classes(array('btn btn-primary col-md-6 col-md-offset-3'))
			->set_value('Register Runner')
			->set_label('Register Runner');
				
		$eventFieldSet->add_element($racenumber);
		$eventFieldSet->add_element($race_drop_down);
		$eventFieldSet->add_element($money_drop_down);
		//$eventFieldSet->add_element($submit);

		$firstname = WP_Form_Element::create('text')->set_name('bhaa_firstname')
			->set_label('First Name')->set_id('bhaa_firstname')->set_classes(array('col-md-8 form-control'));
		$lastname = WP_Form_Element::create('text')->set_name('bhaa_lastname')
			->set_label('Last Name')->set_id('bhaa_lastname')->set_classes(array('col-md-8 form-control'));
		$dateofbirth = WP_Form_Element::create('text')->set_name('bhaa_dateofbirth')
			->set_attribute('placeholder','19YY-MM-DD')
			->set_label('DoB')->set_id('bhaa_dateofbirth')->set_classes(array('col-md-8'));		
		$gender_drop_down = WP_Form_Element::create('radios')->set_name('bhaa_gender')
			->set_label('Gender')->set_classes(array('radio-inline'))
			->add_option('M','M')
			->add_option('W','W');
	
		$runnerFieldSet->add_element($firstname);
		$runnerFieldSet->add_element($lastname);
		$runnerFieldSet->add_element($dateofbirth);
		$runnerFieldSet->add_element($gender_drop_down);
		
		$form->add_element($eventFieldSet)
			->add_element($runnerFieldSet)
			->add_element($submit)
			->add_validator(array($this,'bhaa_day_validation_callback'))
			->add_processor(array($this,'bhaa_day_processing_callback'));
	}
	
	public function bhaa_day_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('bhaa_race');
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		$standard = $submission->get_value('bhaa_standard');
		$money = $submission->get_value('bhaa_money');
		
		//error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		
		$race = $submission->get_value('bhaa_race');
		if(!isset($race))
			$submission->add_error('bhaa_race', 'Select a Race');
		
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		if($racenumber==0)
			$submission->add_error('bhaa_racenumber', 'Enter a valid race number');

		global $wpdb;
		$runnerCount = $wpdb->get_var(
			$wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and runner=%d)',$race,$runner));
		if($runnerCount)
			$submission->add_error('bhaa_runner', 'Runner with id '.$runner.' is already registered!');
		
		$numberCount = $wpdb->get_var(
			$wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and racenumber=%d)',$race,$racenumber)
		);
		if($numberCount)
			$submission->add_error('bhaa_racenumber', 'Race number '.$racenumber.' has already been assigned!');
		
		$money = $submission->get_value('bhaa_money');
		if(!isset($money))
			$submission->add_error('bhaa_money', 'Enter the money paid!');
	}
	
	public function bhaa_day_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('bhaa_race');
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		$standard = $submission->get_value('bhaa_standard');
		$money = $submission->get_value('bhaa_money');
		
		if($runner=='') {
			$runner = Runner_Manager::get_instance()->addNewMember(
				$submission->get_value('bhaa_firstname'),
				$submission->get_value('bhaa_lastname'),
				$submission->get_value('bhaa_gender'),
				$submission->get_value('bhaa_dateofbirth'));
		}
		
		//error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		Raceday::get_instance()->registerRunner($race, $runner, $racenumber, 30, $money);
		
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('/raceday-latest');//home_url('aPage'));
	}
}
?>