<?php 
class Raceday_Registration_Form {
	
	public function build_form(WP_Form $form) {
		$form->add_class('wp-form');
		$args = func_get_args();
		call_user_func_array(array($this, 'bhaaRegisterForm'), $args);
	}
	
	/**
	 *
	 * http://jsfiddle.net/kY5LL/12/
	 * @param unknown $form
	 */
	private function bhaaRegisterForm(WP_Form $form) {
		error_log('bhaaRegisterForm');
	
		//$firstname = WP_Form_Element::create('text')->set_name('firstname')->set_label('First Name')->set_id('firstname');
		//$lastname = WP_Form_Element::create('text')->set_name('lastname')->set_label('Last Name')->set_id('lastname');
	
		//$submit = ;
	
		$form
			->add_element( WP_Form_Element::create('number')
				->set_name('number')->set_id('number')
				->set_label('Race Number'))
			->add_element( WP_Form_Element::create('number')
				->set_name('runner')->set_id('runner')
				->set_label('BHAA ID'))
			->add_element(WP_Form_Element::create('text')->set_name('firstname')->set_label('First Name')->set_id('firstname'))
			->add_element(WP_Form_Element::create('text')->set_name('lastname')->set_label('Last Name')->set_id('lastname'))
			->add_element(WP_Form_Element::create('submit')
				->set_name('submit')
				->set_label('Register Runner'))
			->add_validator(array($this,'bhaa_validation_callback'));
		//$form->add_validator(array($this,'bhaa_validation_callback'),10);
		//$form->add_processor(array($this,'bhaa_processing_callback'),10);
		
//		error_log('validators BHAA '.print_r($form->get_validators(),true));
		//error_log('BHAA FORM '.print_r($form,true));
	}
	
	public function filter_button_views( $decorators, $element ) {
		if ( $element->type == 'text' ) {
			$decorators = array(
					'WP_Form_Decorator_HtmlTag' => array('tag' => 'div',
							'attributes' => array( 'class' => 'control-group' )),
			);
		}
		return $decorators;
	}
	
	public function bhaa_processing_callback( WP_Form_Submission $submission, WP_Form $form ) {
		$first_name = $submission->get_value('firstname');
		// do something with $first_name
		error_log('firstname '.$first_name);
		// redirect the user after the form is submitted successfully
		$submission->set_redirect('');//home_url('aPage'));
	}
	
	public function bhaa_validation_callback( WP_Form_Submission $submission, WP_Form $form ) {
		error_log('bhaavalidation_callback');
		//if ( $submission->get_value('first_name') != 'Jonathan' ) {
		//	$submission->add_error('first_name', 'Your name should be Jonathan');
		//}
	}
}
?>