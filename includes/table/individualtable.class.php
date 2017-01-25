<?php

/**
 * @author oconnellp
 */
class Individual_Table {

	protected static $instance = null;

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function __construct() {
	}

	function renderTable($race) {
		$raceResult = new RaceResult($race);
		$results = $raceResult->getRaceResults();

		return Bhaa_Mustache::get_instance()
			->loadTemplate('race.results.individual.html')
			->render(array('runners'=>$results)
		);
	}

	function renderRunnerTable($runner) {
		$raceResult = new RaceResult();
		$results = $raceResult->getRunnerResults($runner);

		return Bhaa_Mustache::get_instance()
			->loadTemplate('runner.results.individual.html')
			->render(array(
					'runners'=>$results,
					'url'=>get_site_url())
			);
		return '';
	}
}
?>