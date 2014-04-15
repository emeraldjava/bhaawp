<?php
class Raceresult_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('form-horizontal');
		$form->set_attribute('autocomplete','off');
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaa_raceresult_form'), $args);
	}
	
	private function bhaa_raceresult_form(WP_Form $form) {
	
		$runner = WP_Form_Element::create('number')
			->set_name('bhaa_runner')
			->set_id('bhaa_runner')
			->set_label('BHAA ID')
			->set_classes(array('form-control'));
		
		$form->	add_element($runner)
			->add_validator(array($this,'bhaa_raceresult_validation'))
			->add_processor(array($this,'bhaa_raceresult_processing'));
	}

	public function bhaa_raceresult_validation(WP_Form_Submission $submission, WP_Form $form) {
		error_log("bhaa_raceresult_validation");
	}
	
	public function bhaa_raceresult_processing(WP_Form_Submission $submission, WP_Form $form){
		error_log("bhaa_raceresult_processing");
	}
}
?>