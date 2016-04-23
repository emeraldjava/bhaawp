<?php
class Raceday_Registration_Form extends Raceday_Form {

	function __construct() {
		parent::__construct();

		$this->money_drop_down
			->add_option(1,'10e Member')
			->add_option(3,'25e Renew')
			->add_option(2,'20e Day');
	}

	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		$form->set_attribute('autocomplete','off');

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

	private function bhaaRegisterForm(WP_Form $form) {
		$eventFieldSet = WP_Form_Element::create('fieldset')
			->set_classes(array('col-md-4'))
			->set_name('raceFieldSet')
			->set_label('<b>Event Details</b>');
		$runnerFieldSet = WP_Form_Element::create('fieldset')
			->set_classes(array('col-md-4'))
			->set_name('runnerFieldSet')
			->set_label('<b>Runner Details</b>');
		$bhaaFieldSet = WP_Form_Element::create('fieldset')
			->set_classes(array('col-md-4'))
			->set_name('bhaaFieldSet')
			->set_label('<b>BHAA Details</b>');

		// submit button
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_classes(array('btn btn-primary'))
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
			->set_label('Date of Birth')
			->set_id('bhaa_dateofbirth')
			->set_classes(array('form-control'));

		$runnerFieldSet->add_element($firstname);
		$runnerFieldSet->add_element($lastname);
		//$runnerFieldSet->add_element($dateofbirth);
		$runnerFieldSet->add_element($this->gender_drop_down);

		// bhaa field set
		$runner = WP_Form_Element::create('text')
			->set_name('bhaa_runner')
			->set_id('bhaa_runner')
			->set_label('BHAA ID')
			->set_classes(array('form-control'));
		$company = WP_Form_Element::create('text')
			->set_name('bhaa_company')
			->set_label('Company')
			->set_id('bhaa_company')
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
			->add_validator(array($this,'bhaa_validation_callback'))
			->add_processor(array($this,'bhaa_processing_callback'));
	}

	public function bhaa_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		parent::bhaa_common_validation($submission,$form);
	}

	public function bhaa_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {

		$race = $submission->get_value('bhaa_race');
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		$standard = $submission->get_value('bhaa_standard');
		$money = $submission->get_value('bhaa_money');

		error_log(sprintf('bhaa_processing_callback(race:%s, runner:%s, raceno:%s, std:%s, money:%se)',$race,$runner,$racenumber,$standard,$money));
		Raceday::get_instance()->registerRunner($race, $runner, $racenumber, $standard, $money);

		// redirect the user after the form is submitted successfully
		$submission->set_redirect('/raceday-list');
	}
}
?>
