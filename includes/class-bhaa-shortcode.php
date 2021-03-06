<?php
/**
 * Handles the BHAA shortcodes
 * @author oconnellp
 */
class Bhaa_Shortcode {
	
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
		add_shortcode('bhaa_runner_id',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_id'));
		add_shortcode('bhaa_runner_name',array(Runner_Manager::get_instance(),'bhaa_runner_name_shortcode'));
		add_shortcode('bhaa_runner_standard',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_standard'));
		add_shortcode('bhaa_runner_status',array(Runner_Manager::get_instance(),'bhaa_runner_status_shortcode'));
		add_shortcode('bhaa_runner_company_name',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_company_name'));
		add_shortcode('bhaa_runner_results',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_results'));
		add_shortcode('bhaa_runner_sectorteam_name',array(Bhaa_Shortcode::get_instance(),'bhaa_runner_sectorteam_name'));
				
		// admin specific runner shortcodes
		add_shortcode('bhaa_runner_edit_name',array(Runner_Manager::get_instance(),'bhaa_runner_edit_name_shortcode'));
		add_shortcode('bhaa_runner_edit_email',array(Runner_Manager::get_instance(),'bhaa_runner_edit_email_shortcode'));
		add_shortcode('bhaa_runner_edit_dob',array(Runner_Manager::get_instance(),'bhaa_runner_edit_dob_shortcode'));
		add_shortcode('bhaa_runner_edit_gender',array(Runner_Manager::get_instance(),'bhaa_runner_edit_gender_shortcode'));
		add_shortcode('bhaa_runner_edit_mobile',array(Runner_Manager::get_instance(),'bhaa_runner_edit_mobile_shortcode'));
		add_shortcode('bhaa_runner_edit_standard',array(Runner_Manager::get_instance(),'bhaa_runner_edit_standard_shortcode'));
		add_shortcode('bhaa_runner_matches',array(Runner_Manager::get_instance(),'bhaa_runner_matches_shortcode'));
		add_shortcode('bhaa_runner_renew',array(Runner_Manager::get_instance(),'bhaa_renewal_button_shortcode'));
		add_shortcode('bhaa_runner_deactivate',array(Runner_Manager::get_instance(),'bhaa_deactivate_button_shortcode'));
				
		// house-team specific shortcodes
		add_shortcode('house_title',array(Bhaa_Shortcode::get_instance(),'house_title'));
		add_shortcode('house_sector',array(Bhaa_Shortcode::get_instance(),'house_sector'));
		add_shortcode('house_runner_table',array(Bhaa_Shortcode::get_instance(),'house_runner_table'));
		add_shortcode('house_website_url',array(Bhaa_Shortcode::get_instance(),'house_website_url'));
		add_shortcode('house_image',array(Bhaa_Shortcode::get_instance(),'house_image_shortcode'));
		
		// league related shortcodes
		add_shortcode('bhaa_league',array(Bhaa_Shortcode::get_instance(),'bhaa_league'));
		
		// race related shortcodes
		add_shortcode('bhaa_race_title',array(Bhaa_Shortcode::get_instance(),'bhaa_race_title'));
		
		// booking email shortcodes
		add_shortcode('bhaa_booking_details',array(Events_Manager::get_instance(),'bhaa_booking_details_shortcode'));
		add_shortcode('bhaa_booking_annual_membership',array(Events_Manager::get_instance(),'bhaa_booking_annual_membership_shortcode'));
		add_shortcode('bhaa_booking_member_ticket',array(Events_Manager::get_instance(),'bhaa_booking_member_ticket_shortcode'));
		add_shortcode('bhaa_booking_day_ticket',array(Events_Manager::get_instance(),'bhaa_booking_day_ticket_shortcode'));
	}	
		
	/**
	 * Return the BHAA Runners ID as a URL link.
	 */
	function bhaa_runner_id($atts) {
		return sprintf('<a href="%s/runner/?id=%d">%d</a>',get_site_url(),get_query_var('id'),get_query_var('id'));
	}
	
	function bhaa_runner_standard($atts) {
		$runner = new Runner(get_query_var('id'));
		return $runner->getStandard();
	}
	
	/**
	 * admin_url = true | false
	 * @param unknown $atts
	 */
	function bhaa_runner_company_name($atts) {
		// Set the default admin_url to false
		//error_log('$atts '.print_r($atts,true));
		$a = shortcode_atts(
			array( 'admin_url' => "false" ),
			$atts
		);
		//error_log('$a '.print_r($a,true));
		
		if(current_user_can('edit_users')){
			$a['admin_url']="true";
			
			if($atts['admin_url']==="false")
				$a['admin_url']="false";
		}
		//error_log('admin_url '.print_r($a,true));
		$runner = new Runner(get_query_var('id'));
		return $runner->getCompanyName($a['admin_url']);
	}
	
	/**
	 * Return the runners sector team details
	 * @param unknown $atts
	 */
	function bhaa_runner_sectorteam_name($atts) {
		$a = shortcode_atts(
			array( 'admin_url' => "false" ),
			$atts
		);
		
		if(current_user_can('edit_users')){
			$a['admin_url']="true";
				
			if($atts['admin_url']==="false")
				$a['admin_url']="false";
		}
		$runner = new Runner(get_query_var('id'));
		return $runner->getSectorTeam($a['admin_url']);
	}
	
	function bhaa_runner_results($args) {
		return Individual_Table::get_instance()->renderRunnerTable(get_query_var('id'));
	}

	/**
	 * Race specific shortcodes
	 */
	function bhaa_race_title() {
		$race = new Race(get_the_ID());
		return 'Race '.$race->getTitle().' on date '.$race->getDate();
	}
	
	/**
	 * Return the house title as plaintext or edit URL for admin users. 
	 */
	function house_title($args) {
		if(current_user_can('edit_users')) {
			return sprintf('<a target="_blank" href="%s">Edit %s</a>',get_edit_post_link(get_the_ID()),get_the_title(get_the_ID()));
		} else {
			return get_the_title();
		}
	}
	
	/**
	 * Return the sector this house belongs to.
	 * @param unknown $args
	 * @return Ambigous <string, boolean, WP_Error, multitype:, mixed>
	 */
	function house_sector($args) {
		return get_the_term_list(get_the_ID(), 'sector', 'Sector : ', ', ', '');//.' terms '.the_terms(get_the_ID(), 'sector', 'categories: ', ' / ' );;
	}

	/**
	 * Return the post thumbnail
	 */	
	function house_image_shortcode() {
		return get_the_post_thumbnail(get_the_ID(),'medium');
	}
	
	function house_runner_table($args) {
		$house = new House(get_the_ID());
		return $house->displayRunnersTable();
	}
	
	function house_website_url() {
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
		$league = LeagueFactory::getLeague($leagueid);
		return $league->getTopParticipantsInDivision($a['division'],$a['top']);
	}
}
?>