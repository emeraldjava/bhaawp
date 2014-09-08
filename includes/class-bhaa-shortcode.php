<?php
/**
 * Handles the BHAA shortcodes
 * @author oconnellp
 */
class Bhaa_Shortcode{
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
	}
	
	/**
	 * Return a specific 'runner_id' race results
	 * @param unknown $args
	 */
	function bhaa_runner($args) {
		$this_runner = get_query_var('runner_id');
		//error_log('this_runner '.$this_runner);
		echo RaceResult_List_Table::get_instance()->renderRunnerTable($this_runner);
	}
}
?>