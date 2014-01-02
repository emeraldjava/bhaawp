<?php 
class Raceday_Registration_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		$form->set_attribute('align','left');
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaaRegisterForm'), $args);
	}
	
	/**
	 *
	 */
	private function bhaaRegisterForm(WP_Form $form) {
		
		$race_inputs = WP_Form_Element::create('fieldset')->
			set_name('race_inputs')->set_label('Race Details');//->set_classes('row')->set_classes(' col-md-6');
		$runner_inputs = WP_Form_Element::create('fieldset')->
			set_name('runner_inputs')->set_label('Runner Details');//->set_classes('row col-md-6');
		
		//$form
		$number = WP_Form_Element::create('number')
				->set_name('number')->set_id('number')
				->set_label('Race Number')
				->set_classes(array('form-group'))->set_attribute('class','form-group');

		$race_drop_down = WP_Form_Element::create('radios')->set_name('single_select')->set_label('Race')->set_classes('form-group');
		$race_drop_down
			->add_option(W, '2M Women')
			->add_option(M, '4Mile Men');
		
		$runner = WP_Form_Element::create('number')
				->set_name('runner')->set_id('runner')
				->set_label('BHAA ID')
				->set_classes('form-group');

		$firstname = WP_Form_Element::create('text')->set_name('firstname')->set_label('First Name')->set_id('firstname')->set_classes('form-group');
		$lastname = WP_Form_Element::create('text')->set_name('lastname')->set_label('Last Name')->set_id('lastname')->set_classes('form-group');
		
/*		$race_inputs->add_element($number);
		$race_inputs->add_element($runner);
		$runner_inputs->add_element($firstname);
		$runner_inputs->add_element($lastname);*/
		
		$form->set_attribute('role','form')
			->add_element($number)
			->add_element($race_drop_down)
			->add_element($runner)
			->add_element($firstname)
			->add_element($lastname)
			->add_element(WP_Form_Element::create('submit')
				->set_name('submit')
				->set_value('Register Runner')
				->set_label('Register Runner')
				->set_classes('form-group'))
			->add_validator(array($this,'bhaa_validation_callback'))
			->add_processor(array($this,'bhaa_processing_callback'));
	}
	
	public function bhaa_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		error_log('bhaavalidation_callback');
		//if ( $submission->get_value('first_name') != 'Jonathan' ) {
		//	$submission->add_error('first_name', 'Your name should be Jonathan');
		//}
	}
	
	public function bhaa_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		error_log('bhaa_processing_callback()');
		$runner = $submission->get_value('runner');
		$number = $submission->get_value('number');
		// do something with $first_name
		error_log('runner '.$runner);
		error_log('number '.$number);
		Raceday::get_instance()->registerRunner(3216, $runner, $number, 1, 10);
		
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('');//home_url('aPage'));
	}
}
?>