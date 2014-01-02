<?php 
class Raceday_DayMember_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		$form->set_attribute('align','left');
		$args = func_get_args();
		call_user_func_array(array($this, 'dayMemberForm'), $args);
	}
	
	/**
	 *
	 */
	private function dayMemberForm(WP_Form $form) {
		
		$race_inputs = WP_Form_Element::create('fieldset')->
			set_name('race_inputs')->set_label('Race Details');//->set_classes('row')->set_classes(' col-md-6');
		$runner_inputs = WP_Form_Element::create('fieldset')->
			set_name('runner_inputs')->set_label('Runner Details');//->set_classes('row col-md-6');
		
		//$form
		$racenumber = WP_Form_Element::create('number')
				->set_name('racenumber')->set_id('racenumber')
				->set_label('Race Number')->set_classes(array('form-group'));

		$race_drop_down = WP_Form_Element::create('radios')->set_name('race')->set_label('Race')->set_classes(array('form-group'));
		$race_drop_down
			->add_option(3254,'4Mile Men')
			->add_option(3252,'2M Women');
			
		$money_drop_down = WP_Form_Element::create('radios')->set_name('money')->set_label('Money')->set_classes(array('form-group'));
		$money_drop_down
			->add_option(5,'25e New Member')
			->add_option(4,'15e Day');
		
		$runner = WP_Form_Element::create('number')
				->set_name('runner')->set_id('runner')
				->set_label('BHAA ID')
				->set_classes(array('form-group'));

		$firstname = WP_Form_Element::create('text')->set_name('firstname')->set_label('First Name')->set_id('firstname')->set_classes(array('form-group'));
		$lastname = WP_Form_Element::create('text')->set_name('lastname')->set_label('Last Name')->set_id('lastname')->set_classes(array('form-group'));
		
		$gender_drop_down = WP_Form_Element::create('radios')->set_name('gender')->set_label('Gender')->set_classes(array('form-group'));
		$gender_drop_down
			->add_option(M,'M')
			->add_option(W,'W');
		
		$dateofbirth = WP_Form_Element::create('text')->set_name('dateofbirth')->set_label('DoB')->set_id('dateofbirth')->set_classes(array('form-group'));
		$company = WP_Form_Element::create('text')->set_name('company')->set_label('Company')->set_id('company')->set_classes(array('form-group'));
		$standard = WP_Form_Element::create('text')->set_name('standard')->set_label('Standard')->set_id('standard')->set_classes(array('form-group'));
		
		
/*		$race_inputs->add_element($number);
		$race_inputs->add_element($runner);
		$runner_inputs->add_element($firstname);
		$runner_inputs->add_element($lastname);*/
		
		$form->set_attribute('role','form')
			->add_element($racenumber)
			->add_element($race_drop_down)
			->add_element($money_drop_down)
			//->add_element($runner)
			->add_element($firstname)
			->add_element($lastname)
			->add_element($gender_drop_down)
			->add_element($dateofbirth)
			//->add_element($company)
			//->add_element($standard)
			->add_element(WP_Form_Element::create('submit')
				->set_name('submit')
				->set_value('Register Runner')
				->set_label('Register Runner')
				->set_classes(array('form-group')))
			->add_validator(array($this,'bhaa_day_validation_callback'))
			->add_processor(array($this,'bhaa_day_processing_callback'));
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