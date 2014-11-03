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
		add_shortcode('bhaa_runner_name',array(Runner_Manager::get_instance(),'bhaa_runner_name_shortcode'));
		add_shortcode('bhaa_runner_id',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_id'));
		add_shortcode('bhaa_runner_standard',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_standard'));
		add_shortcode('bhaa_runner_status',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_status'));
		add_shortcode('bhaa_runner_company_name',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_company_name'));
		add_shortcode('bhaa_runner_results',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_results'));
		add_shortcode('bhaa_runner_renew',array(Runner_Manager::get_instance(),'renewal_button_shortcode'));
		add_shortcode('bhaa_runner_email',array(Runner_Manager::get_instance(),'bhaa_runner_email_shortcode'));
		add_shortcode('bhaa_runner_dob',array(Runner_Manager::get_instance(),'bhaa_runner_dob_shortcode'));
		add_shortcode('bhaa_runner_matches',array(Runner_Manager::get_instance(),'bhaa_runner_matches_shortcode'));
		
		// house-team specific shortcodes
		add_shortcode('house_title',array(Bhaa_Shortcode::get_instance(),'house_title'));
		add_shortcode('house_sector',array(Bhaa_Shortcode::get_instance(),'house_sector'));
		add_shortcode('house_runner_table',array(Bhaa_Shortcode::get_instance(),'house_runner_table'));
		add_shortcode('house_website_url',array(Bhaa_Shortcode::get_instance(),'house_website_url'));
		
		// league related shortcodes
		add_shortcode('bhaa_league',array(Bhaa_Shortcode::get_instance(),'bhaa_league'));
		
		// race related shortcodes
		add_shortcode('bhaa_race_title',array(Bhaa_Shortcode::get_instance(),'bhaa_race_title'));
		add_shortcode('bhaa_race_results',array(Bhaa_Shortcode::get_instance(),'bhaa_race_results'));
		add_shortcode('bhaa_race_edit_result',array(Bhaa_Shortcode::get_instance(),'bhaa_race_edit_result'));
	}	
		
	/**
	 * Return the runners BHAA ID
	 */
	function bhaa_runner_id($atts) {
		return get_query_var('id');
	}
	
	function bhaa_runner_standard($atts) {
		$runner = new Runner(get_query_var('id'));
		return $runner->getStandard();
	}
	
	function bhaa_runner_status($atts) {
		$runner = new Runner(get_query_var('id'));
		return $runner->getStatus();
	}
	
	function bhaa_runner_company_name($atts) {
		$runner = new Runner(get_query_var('id'));
		return $runner->getCompanyName();
	}
	
	function bhaa_runner_results($args) {
		return RaceResult_List_Table::get_instance()->renderRunnerTable(get_query_var('id'));
	}

	/**
	 * Race specific shortcodes
	 */
	function bhaa_race_title() {
		$race = new Race(get_the_ID());
		return 'Race '.$race->getTitle().' on date '.$race->getDate();
	}
	
	function bhaa_race_results() {
		return RaceResult_List_Table::get_instance()->renderTable(get_the_ID());
	}
	
	function bhaa_race_edit_result() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		return '<a href="'.post_permalink(get_query_var('bhaa_race')).'">'
			.post_permalink(get_query_var('bhaa_race')).' '.get_query_var('bhaa_race').'</a><br/>'
			.wp_get_form('raceResultForm');
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
	
	function house_runner_table($args){
		$house = new House(get_the_ID());
		return $house->displayRunnersTable();
	}
	
	function house_website_url(){
		$house = new House(get_the_ID());
		return $house->house_website_url();
	}
	
	/**
	 * League Division short codes
	 */
	function bhaa_league($attrs) {
		$a = shortcode_atts( array(
			'division' => 'A',
			'top' => '10',
		), $attrs );
		
		$leagueid = get_the_ID();
		//$post = get_post( $id );
		
		$league = LeagueFactory::getLeague($leagueid);
		return $league->getTopParticipantsInDivision($a['division'],$a['top']);
		//return $a['division'].'-'.$a['top'].'-'.$id.' '.$league->getName();
	}
}
?>