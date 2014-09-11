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
		add_shortcode('bhaa_runner',array(Bhaa_Shortcode::get_instance(),'bhaa_runner'));
		add_shortcode('house_title',array(Bhaa_Shortcode::get_instance(),'house_title'));
		add_shortcode('house_sector',array(Bhaa_Shortcode::get_instance(),'house_sector'));
	}	
	
	/**
	 * Return a specific 'runner_id' race results
	 * @param unknown $args
	 */
	function bhaa_runner($args) {
		$this_runner = get_query_var('id');//'runner_id'
		//error_log('this_runner '.$this_runner);
		return RaceResult_List_Table::get_instance()->renderRunnerTable(7713);//$this_runner);
	}
	
	function post_title($args) {
	   return get_the_title();
	}
	
	function house_title($args) {
		return get_the_title();
	}
	
	function house_sector($args){
		return get_the_term_list(get_the_ID(), 'sector', 'Sector : ', ', ', '');
	}
}
?>