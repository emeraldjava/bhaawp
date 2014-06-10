<?php
class Raceresult_Form {
	
	public function build_form(WP_Form $form) {
		$form->set_attribute('autocomplete','off');
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaa_raceresult_form'), $args);
	}
	
	private function bhaa_raceresult_form(WP_Form $form) {
	
		$fieldSet = WP_Form_Element::create('fieldset')
			->set_name('raceFieldSet')
			->set_label('Race Details')
			->set_classes(array('col-md-8'));
		
		$raceresult_id = WP_Form_Element::create('number')
			->set_name('bhaa_raceresult_id')
			->set_id('bhaa_raceresult_id')
			->set_label('Result')
			->set_value($_GET['bhaa_raceresult_id'])
			->set_classes(array('form-control'));
			
		$runner = WP_Form_Element::create('number')
			->set_name('bhaa_runner')
			->set_id('bhaa_runner')
			->set_label('BHAA ID')
			->set_value($_GET['bhaa_runner'])
			->set_classes(array('form-control'));
		
		$race = WP_Form_Element::create('number')
			->set_name('bhaa_race')
			->set_id('bhaa_race')
			->set_label('Race')
			->set_value($_GET['bhaa_race'])
			->set_classes(array('form-control'));
		
		$time = WP_Form_Element::create('text')
			->set_name('bhaa_time')
			->set_id('bhaa_time')
			->set_label('Time')
			->set_value($_GET['bhaa_time'])
			->set_classes(array('form-control'));
		
		$bhaa_pre_standard = WP_Form_Element::create('number')
			->set_name('bhaa_pre_standard')
			->set_id('bhaa_pre_standard')
			->set_label('Pre Standard')
			->set_value($_GET['bhaa_pre_standard'])
			->set_classes(array('form-control'));
		
		$bhaa_post_standard = WP_Form_Element::create('number')
			->set_name('bhaa_post_standard')
			->set_id('bhaa_post_standard')
			->set_label('Post Standard')
			->set_value($_GET['bhaa_post_standard'])
			->set_classes(array('form-control'));
		
		$submit = WP_Form_Element::create('submit')
			->set_name('submit')
			->set_classes(array('btn btn-info'))
			->set_value('Update Race Result');
			
		$fieldSet->add_element($raceresult_id);
		$fieldSet->add_element($race);
		$fieldSet->add_element($runner);
		$fieldSet->add_element($time);
		$fieldSet->add_element($bhaa_pre_standard);
		$fieldSet->add_element($bhaa_post_standard);
		$fieldSet->add_element($submit);
		
		$form->	add_element($fieldSet)
			->add_validator(array($this,'bhaa_raceresult_validation'))
			->add_processor(array($this,'bhaa_raceresult_processing'));
	}

	public function bhaa_raceresult_validation(WP_Form_Submission $submission, WP_Form $form) {
		error_log("bhaa_raceresult_validation");
	}
	
	public function bhaa_raceresult_processing(WP_Form_Submission $submission, WP_Form $form) {
		error_log("bhaa_raceresult_processing");
		$raceResult = new RaceResult($submission->get_value('bhaa_race'));
		$raceResult->updateRunnersRaceResultStandard(
			$submission->get_value('bhaa_raceresult_id'),
			$submission->get_value('bhaa_race'),
			$submission->get_value('bhaa_runner'),
			$submission->get_value('bhaa_time'),
			$submission->get_value('bhaa_pre_standard'),
			$submission->get_value('bhaa_post_standard')
		);
	}
}
?>