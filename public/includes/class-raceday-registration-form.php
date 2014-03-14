<?php 
class Raceday_Registration_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		//add_filter('wp_form_label_html_class',array($this,'bhaa_wp_form_label_html_class'));
		//add_filter('wp_form_htmltag_default',array($this,'bhaa_wp_form_htmltag_default'));
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaaRegisterForm'), $args);
		//add_filter('wp_form_default_decorators',array( $this, 'filter_default_decorators' ), 10, 2);
	}
	
	/*public function filter_default_decorators(array $decorators, WP_Form_Element $element){
		foreach ( $decorators as $decorator_class => $decorator_args ){
			if ( $decorator_class == 'WP_Form_Decorator_HtmlTag' ) {
				$decorator_args['attributes'] = array("class='form-group'");
			}
		}
	}*/

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
		
		$eventFieldSet = WP_Form_Element::create('fieldset')
			->set_classes(array('col-md-3'))
			->set_name('raceFieldSet')
			->set_label('Event Details');
		$runnerFieldSet = WP_Form_Element::create('fieldset')
			->set_classes(array('col-md-5'))
			->set_name('runnerFieldSet')
			->set_label('Runner Details');
		$bhaaFieldSet = WP_Form_Element::create('fieldset')
			->set_classes(array('col-md-4'))
			->set_name('bhaaFieldSet')
			->set_label('BHAA Details');
		
		// race day field set
		$racenumber = WP_Form_Element::create('number')
			->set_name('bhaa_racenumber')
			->set_id('bhaa_racenumber')
			->set_label('Race Number')
			->set_attribute('placeholder','Race Number')
			->set_classes(array('form-control'));

		$race_drop_down = WP_Form_Element::create('radios')
			->set_name('bhaa_race')
			->set_label('Race');
		$race_drop_down
			->add_option(3256,'5Mile Men')
			->add_option(3255,'2M Women');
			
		$money_drop_down = WP_Form_Element::create('radios')
			->set_name('bhaa_money')
			->set_label('Money');
		$money_drop_down
			->add_option(1,'10e Member')
			->add_option(3,'25e Renew')
			->add_option(2,'15e Day');
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_classes(array('btn btn-primary'))//col-md-6 col-md-offset-3
			->set_value('Register Runner')
			->set_label('Register Runner');
				
		$eventFieldSet->add_element($racenumber);
		$eventFieldSet->add_element($race_drop_down);
		$eventFieldSet->add_element($money_drop_down);
		$eventFieldSet->add_element($submit);
		
		$firstname = WP_Form_Element::create('text')
			->set_name('bhaa_firstname')
			->set_label('First Name')
			->set_id('bhaa_firstname')
			->set_classes(array('form-control'));//col-sm-8
		$lastname = WP_Form_Element::create('text')
			->set_name('bhaa_lastname')
			->set_label('Last Name')
			->set_id('bhaa_lastname')
			->set_classes(array('form-control'));//col-sm-8
		
		$gender_drop_down = WP_Form_Element::create('radios')
			->set_name('bhaa_gender')
			->set_label('Gender')
			->set_classes(array('radio-inline'));
		$gender_drop_down
			->add_option('M','M')
			->add_option('W','W');
		
		$dateofbirth = WP_Form_Element::create('text')
			->set_name('bhaa_dateofbirth')
			->set_label('DoB')
			->set_id('bhaa_dateofbirth')
			->set_classes(array('form-control'));//col-sm-8
		
		$runnerFieldSet->add_element($firstname);
		$runnerFieldSet->add_element($lastname);
		$runnerFieldSet->add_element($dateofbirth);
		$runnerFieldSet->add_element($gender_drop_down);
		
		// bhaa field set
		$runner = WP_Form_Element::create('number')
			->set_name('bhaa_runner')
			->set_id('bhaa_runner')
			->set_label('BHAA ID')
			->set_classes(array('form-control'));
		$company = WP_Form_Element::create('text')
			->set_name('company')
			->set_label('Company')
			->set_id('company')
			->set_classes(array('form-control'));
		$standard = WP_Form_Element::create('text')
			->set_name('bhaa_standard')
			->set_label('Standard')
			->set_id('bhaa_standard')
			->set_classes(array('form-control'));
		
		$bhaaFieldSet->add_element($runner);
		$bhaaFieldSet->add_element($company);
		$bhaaFieldSet->add_element($standard);
		
		$form->add_element($eventFieldSet)
			->add_element($runnerFieldSet)
			->add_element($bhaaFieldSet)
			//->add_element($submit)
			->add_validator(array($this,'bhaa_validation_callback'))
			->add_processor(array($this,'bhaa_processing_callback'));	
	}
	
	public function bhaa_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
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
		if($racenumber==0) {
			$submission->add_error('bhaa_racenumber', 'Enter a valid race number');
			$form->get_element('bhaa_racenumber')->set_classes(array('form-control has-error'));
		}

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
		
		//if(!isset($submission->get_value('bhaa_gender')))
			//$submission->add_error('bhaa_gender', 'Select a Race');
		
		$money = $submission->get_value('bhaa_money');
		if(!isset($money))
			$submission->add_error('bhaa_money', 'Enter the money paid!');
		
		//error_log(print_r($submission->get_errors(),false));
	}
	
	public function bhaa_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		
		$race = $submission->get_value('bhaa_race');
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		$standard = $submission->get_value('bhaa_standard');
		$money = $submission->get_value('bhaa_money');
		
		//error_log(sprintf('bhaa_processing_callback(%s %s %s %s %se)',$race,$runner,$racenumber,$standard,$money));
		Raceday::get_instance()->registerRunner($race, $runner, $racenumber, $standard, $money);
		
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('/raceday-latest');//home_url('aPage'));
	}
}
?>