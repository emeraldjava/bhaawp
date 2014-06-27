<?php
abstract class Raceday_Form {
	
	//private $races = null;
	protected $racenumber = null;
	protected $race_drop_down = null;
	protected $money_drop_down = null;
	 
	function __construct() {
		//$this->races = Raceday::get_instance()->getNextRaces();
		
		$this->race_drop_down = WP_Form_Element::create('hidden')
			->set_name('bhaa_race')
			->set_value('1');
			//->set_label($this->races[0]->dist.' '.$this->races[0]->unit)
			//->set_value($this->races[0]->id)
			//->set_attribute('selected','true');
		//foreach ($this->races as $race ) {
		//	$this->race_drop_down->add_option($race->id,$race->dist.''.$race->unit);
		//}
		
		// race day field set
		$this->racenumber = WP_Form_Element::create('number')
			->set_name('bhaa_racenumber')
			->set_id('bhaa_racenumber')
			->set_label('Race Number')
			->set_attribute('placeholder','Race Number')
			->set_classes(array('form-control'));
		
		$this->money_drop_down = WP_Form_Element::create('radios')
			->set_name('bhaa_money')
			->set_label('Money');
	}

	/**
	 * Validation rules common to both forms 
	 * @param WP_Form_Submission $submission
	 * @param WP_Form $form
	 */
	public function bhaa_common_validation( WP_Form_Submission $submission, WP_Form $form ) {
	
		$race = $submission->get_value('bhaa_race');
		$runner = $submission->get_value('bhaa_runner');
		$racenumber = $submission->get_value('bhaa_racenumber');
		$money = $submission->get_value('bhaa_money');
	
		// race selected	
		$race = $submission->get_value('bhaa_race');
		if(!isset($race))
			$submission->add_error('bhaa_race', 'Select a Race');

		// race number
		$racenumber = $submission->get_value('bhaa_racenumber');
		if($racenumber==0||$racenumber=='')
			$submission->add_error('bhaa_racenumber', 'Enter a valid race number');
	
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
	
		$money = $submission->get_value('bhaa_money');
		if(!isset($money))
			$submission->add_error('bhaa_money', 'Enter the money paid!');	
	}
}
?>