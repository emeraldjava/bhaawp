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

		$isAdmin = current_user_can('manage_options');

		// http://wordpress.stackexchange.com/questions/129618/how-to-redirect-new-wordpress-user-to-previous-page-after-registering
//		add a hidden field named redirect_to, using the current page's address (ie, $_SERVER['PHP_SELF']).

//		return sprintf('<a target="_blank" class="bhaa-url-link" href="/edit-result-page-template?
//			bhaa_raceresult_id=%d&bhaa_runner=%d&bhaa_pre_standard=%d&bhaa_post_standard=%d&bhaa_race=%d&bhaa_time=%s">%d</a>',
//			$item['id'],$item['runner'],$item['standard'],$item['poststandard'],$item['race'],$item['racetime'],$item['position']);

		return Bhaa_Mustache::get_instance()
			->loadTemplate('race.results.individual.html')
			->render(array(
					'runners'=>$results,
					'isAdmin'=>$isAdmin)
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