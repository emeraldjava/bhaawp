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
	
	function registerShortCodes(){
		// runner specific shortcodes
		add_shortcode('bhaa_runner',array(Bhaa_Shortcode::get_instance(),'bhaa_runner'));
		add_shortcode('bhaa_runner_name',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_name'));
		add_shortcode('bhaa_runner_standard',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_standard'));
		add_shortcode('bhaa_runner_status',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_status'));
		add_shortcode('bhaa_runner_company_name',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_company_name'));
		add_shortcode('bhaa_runner_results',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_results'));
		
		// house-team specific shortcodes
		add_shortcode('house_title',array(Bhaa_Shortcode::get_instance(),'house_title'));
		add_shortcode('house_sector',array(Bhaa_Shortcode::get_instance(),'house_sector'));
		
		// league related shortcodes
		add_shortcode('bhaa_league_division',array(Bhaa_Shortcode::get_instance(),'bhaa_league_division'));
	}	
	
	/**
	 * Return a specific 'runner_id' race results
	 * @param unknown $args
	 */
	function bhaa_runner($atts) {
		$bhaaid = get_query_var('bhaaid');
		$runner = new Runner($bhaaid);
		return $runner->getFullName();
	}
	
	function bhaa_runner_name($atts) {
		$runner = new Runner(get_query_var('bhaaid'));
		return $runner->getFullName();
	}
	
	function bhaa_runner_standard($atts) {
		$runner = new Runner(get_query_var('bhaaid'));
		return $runner->getStandard();
	}
	
	function bhaa_runner_status($atts) {
		$runner = new Runner(get_query_var('bhaaid'));
		return $runner->getStatus();
	}
	
	function bhaa_runner_company_name($atts) {
		$runner = new Runner(get_query_var('bhaaid'));
		return $runner->getCompanyName();
	}
	
	function bhaa_runner_results($args) {
		return RaceResult_List_Table::get_instance()->renderRunnerTable(get_query_var('bhaaid'));
	}
	
	/**
	 * House related short codes
	 */
	function house_title($args) {
		return get_the_title();
	}
	
	function house_sector($args){
		return get_the_term_list(get_the_ID(), 'sector', 'Sector : ', ', ', '');
	}
	
	/**
	 * League Division short codes
	 */
	function bhaa_league_division($attrs) {
		$a = shortcode_atts( array(
			'div' => 'A',
			'size' => '10',
		), $attrs );
		return $a['div'].'-'.$a['size'];
	}
}
?>