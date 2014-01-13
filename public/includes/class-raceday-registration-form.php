<?php 
class Raceday_Registration_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		//add_filter('wp_form_label_html_class',array($this,'bhaa_wp_form_label_html_class'));
		//add_filter('wp_form_htmltag_default',array($this,'bhaa_wp_form_htmltag_default'));
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaaRegisterForm'), $args);
	}

	public function bhaa_wp_form_htmltag_default($element){
		return 'div';	
	}
	
	public function bhaa_wp_form_label_html_class() {
		return 'col-md-4 control-label';
	}
	
	/**
	 *
	 */
	private function bhaaRegisterForm(WP_Form $form) {
		
		//$layout = new WP_FormsDemo_TableLayout();
		//$layout->add_hooks();
		
		$raceFieldSet = WP_Form_Element::create('fieldset')->
			set_classes(array('col-md-3'))->
			set_name('raceFieldSet')->set_label('Event');
		$runnerFieldSet = WP_Form_Element::create('fieldset')->
			set_classes(array('col-md-3'))->
			set_name('runnerFieldSet')->set_label('Runner');
		$bhaaFieldSet = WP_Form_Element::create('fieldset')->
			set_classes(array('col-md-3'))->
			set_name('bhaaFieldSet')->set_label('BHAA');
		
		// race day field set
		$racenumber = WP_Form_Element::create('number')
			->set_name('racenumber')->set_id('racenumber')
			->set_label('Race Number')->set_classes(array('form-control'));

		$race_drop_down = WP_Form_Element::create('radios')->set_name('race')->set_label('Race');
//			->set_classes(array('col-md-8'));
		$race_drop_down
			->add_option(3256,'5Mile Men')
			->add_option(3255,'2M Women');
			
		$money_drop_down = WP_Form_Element::create('radios')->set_name('money')->set_label('Money');
//			->set_classes(array('col-md-8'));
		$money_drop_down
			->add_option(1,'10e Member')
			->add_option(3,'25e Renew')
			->add_option(2,'15e Day');
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_value('Register Runner')
			->set_label('Register Runner');
				
		$raceFieldSet->add_element($racenumber);
		$raceFieldSet->add_element($race_drop_down);
		$raceFieldSet->add_element($money_drop_down);
		$raceFieldSet->add_element($submit);
		
		$firstname = WP_Form_Element::create('text')->set_name('firstname')
			->set_label('First Name')->set_id('firstname')->set_classes(array('col-md-8 form-control'));
		$lastname = WP_Form_Element::create('text')->set_name('lastname')
			->set_label('Last Name')->set_id('lastname')->set_classes(array('col-md-8 form-control'));
		
		$gender_drop_down = WP_Form_Element::create('radios')->set_name('gender')->set_label('Gender')->set_classes(array('radio-inline'));
		$gender_drop_down
			->add_option(M,'M')
			->add_option(W,'W');
		
		$dateofbirth = WP_Form_Element::create('text')->set_name('dateofbirth')->set_label('DoB')->set_id('dateofbirth')->set_classes(array('form-group'));
		
		$runnerFieldSet->add_element($firstname);
		$runnerFieldSet->add_element($lastname);
		$runnerFieldSet->add_element($dateofbirth);
		$runnerFieldSet->add_element($gender_drop_down);
		
		// bhaa field set
		$runner = WP_Form_Element::create('number')
			->set_name('runner')->set_id('runner')
			->set_label('BHAA ID')
			->set_classes(array('form-control'));
		$company = WP_Form_Element::create('text')->set_name('company')->set_label('Company')
			->set_id('company')->set_classes(array('form-control'));
		$standard = WP_Form_Element::create('text')->set_name('standard')->set_label('Standard')
			->set_id('standard')->set_classes(array('form-control'));
		
		$bhaaFieldSet->add_element($runner);
		$bhaaFieldSet->add_element($company);
		$bhaaFieldSet->add_element($standard);
		
		$form
		->add_element($raceFieldSet)
		->add_element($runnerFieldSet)
		->add_element($bhaaFieldSet)
		->add_validator(array($this,'bhaa_validation_callback'))
		->add_processor(array($this,'bhaa_processing_callback'));	
	}
	
	public function bhaa_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('race');
		$runner = $submission->get_value('runner');
		$racenumber = $submission->get_value('racenumber');
		$standard = $submission->get_value('standard');
		$money = $submission->get_value('money');
		
		//error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		
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
		
		//if(!isset($submission->get_value('gender')))
			//$submission->add_error('gender', 'Select a Race');
		
		$money = $submission->get_value('money');
		if(!isset($money))
			$submission->add_error('money', 'Enter the money paid!');
	}
	
	public function bhaa_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('race');
		$runner = $submission->get_value('runner');
		$racenumber = $submission->get_value('racenumber');
		$standard = $submission->get_value('standard');
		$money = $submission->get_value('money');
		
		//error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		Raceday::get_instance()->registerRunner($race, $runner, $racenumber, $standard, $money);
		
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('/raceday-latest');//home_url('aPage'));
	}
}
?>