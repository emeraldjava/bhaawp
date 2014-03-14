<?php
abstract class Raceday_Form {
	
	private $races = null;
	protected $race_drop_down = null;
	 
	function __construct() {
		$this->races = Raceday::get_instance()->getNextRaces();
		
		$this->race_drop_down = WP_Form_Element::create('radios')
			->set_name('bhaa_race')
			->set_label('Race');
		foreach ($this->races as $race ) {
			$this->race_drop_down->add_option($race->id,$race->dist.''.$race->unit);
		}
	}

}
?>