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
			set_name('raceFieldSet')->set_label('Event Details')->set_classes(array('col-md-4'));
		$runnerFieldSet = WP_Form_Element::create('fieldset')->
			set_name('runnerFieldSet')->set_label('Runner Details')->set_classes(array('col-md-4'));
		
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
			->add_option(1,'10e Member')
			->add_option(3,'25e Renew')
			->add_option(2,'15e Day');
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_value('Register Runner')
			->set_label('Register Runner');
				
		$eventFieldSet->add_element($racenumber);
		$eventFieldSet->add_element($race_drop_down);
		$eventFieldSet->add_element($money_drop_down);
		$eventFieldSet->add_element($submit);

		$firstname = WP_Form_Element::create('text')->set_name('bhaa_firstname')
			->set_label('First Name')->set_id('bhaa_firstname')->set_classes(array('col-md-8 form-control'));
		$lastname = WP_Form_Element::create('text')->set_name('bhaa_lastname')
			->set_label('Last Name')->set_id('bhaa_lastname')->set_classes(array('col-md-8 form-control'));
		$dateofbirth = WP_Form_Element::create('text')->set_name('bhaa_dateofbirth')
			->set_label('DoB')->set_id('bhaa_dateofbirth')->set_classes(array('form-group'));		
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
			->add_validator(array($this,'bhaa_validation_callback'))
			->add_processor(array($this,'bhaa_processing_callback'));
	}
	
	public function bhaa_day_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('race');
		$runner = $submission->get_value('runner');
		$racenumber = $submission->get_value('racenumber');
		$money = $submission->get_value('money');
		
		error_log(sprintf('bhaa_processing_callback(%s %s %s %se)',$race,$runner,$racenumber,$money));
		
		$race = $submission->get_value('race');
		if(!isset($race))
			$submission->add_error('race', 'Select a Race');
		
		$runner = $submission->get_value('runner');
		$racenumber = $submission->get_value('racenumber');
		if($racenumber==0)
			$submission->add_error('racenumber', 'Enter a valid race number');

		global $wpdb;
		$runnerCount = $wpdb->get_var(
			$wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and runner=%d)',$race,$runner));
		if($runnerCount)
			$submission->add_error('runner', 'Runner with id '.$runner.' is already registered!');
		
		$numberCount = $wpdb->get_var(
			$wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and racenumber=%d)',$race,$racenumber)
		);
		if($numberCount)
			$submission->add_error('racenumber', 'Race number '.$racenumber.' has already been assigned!');
		
		$gender = $submission->get_value('gender');
		if(!isset($gender))
			$submission->add_error('gender', 'Select a gender');
		
		$money = $submission->get_value('money');
		if(!isset($money))
			$submission->add_error('money', 'Enter the money paid!');
	}
	
	public function bhaa_day_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('race');
		$runner = $submission->get_value('runner');
		$racenumber = $submission->get_value('racenumber');
		$standard = $submission->get_value('standard');
		$money = $submission->get_value('money');
		
		error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		//Raceday::get_instance()->registerRunner($race, $runner, $racenumber, $standard, $money);
		
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('./raceday-latest/');//home_url('aPage'));
	}
}
?>