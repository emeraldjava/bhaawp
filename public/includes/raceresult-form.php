<?php
class Raceresult_Form {
	
	public function build_form(WP_Form $form) {
		//$form->add_class('form-horizontal');
		$form->set_attribute('autocomplete','off');
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaa_raceresult_form'), $args);
	}
	
	private function bhaa_raceresult_form(WP_Form $form) {
	
		$fieldSet = WP_Form_Element::create('fieldset')
			->set_name('raceFieldSet')
			->set_label('Race Details')
			->set_classes(array('col-md-8'));
		
		$raceresult_id = WP_Form_Element::create('hidden')
			->set_name('bhaa_raceresult_id')
			->set_id('bhaa_raceresult_id');
			
		$runner = WP_Form_Element::create('number')
			->set_name('bhaa_runner')
			->set_id('bhaa_runner')
			->set_label('BHAA ID')
			->set_classes(array('form-control'));
		
		$race = WP_Form_Element::create('number')
			->set_name('bhaa_race')
			->set_id('bhaa_race')
			->set_label('Race')
			->set_classes(array('form-control'));
		
		$racenumber = WP_Form_Element::create('number')
			->set_name('bhaa_racenumber')
			->set_id('bhaa_racenumber')
			->set_label('Number')
			->set_classes(array('form-control'));
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_classes(array('btn btn-info'))
			->set_value('Update Race Result');
			//->set_label('Update Race Result');
			
		$fieldSet->add_element($raceresult_id);
		$fieldSet->add_element($runner);
		$fieldSet->add_element($race);
		$fieldSet->add_element($racenumber);
		$fieldSet->add_element($submit);
		
		$form->	add_element($fieldSet)
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