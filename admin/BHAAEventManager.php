<?php
/**
 * Handle the BHAA specific Event Manager plugin customisations
 * @author oconnellp
 */
class BHAAEventManager {
	
	function __construct() {
		add_filter('em_event_output_placeholder',array($this,'bhaa_em_event_output_placeholder'),2,4);
		add_filter('em_booking_output_placeholder',array($this,'bhaa_em_booking_output_placeholder'),1,3);

		add_filter('em_ticket_is_available',array($this,'bhaa_em_ticket_is_available'), 10, 2);
		
		
		// em_booking_form_before_tickets
		// em_booking_form_after_tickets
		// em_booking_form_before_user_details
		// em_booking_form_after_user_details
		// em_booking_form_footer
		add_action('em_booking_form_before_tickets',array($this,'bhaa_em_booking_form_before_tickets'),9,2);
		//add_action('em_booking_form_after_user_details',array($this,'bhaa_em_booking_form_after_user_details'),9,2);
		add_action('em_booking_form_footer',array($this,'bhaa_em_booking_form_footer'),9,2);
		
		//add_action('em_form_output_field_custom_house',array($this,'bhaa_em_form_output_field_custom_house'), 10, 2);
		//add_filter('em_form_validate_field_custom',array($this,'bhaa_em_form_validate_field_custom'), 10, 2);
		//add_filter('emp_forms_output_field_input',array($this,'bhaa_emp_forms_output_field_input'),10,2);
	
		// em_form_validate_field_custom
	}
	
	function bhaa_emp_forms_output_field_input(){
		
	}
	
	function bhaa_em_form_output_field_custom_house(){
		
	}
	
	/**
	 * Validate the custom house form field
	 */
	function bhaa_em_form_validate_field_custom() {
		/*case 'house':
			if( $result && trim($value) == '' && !empty($field['required']) ){
				$this->add_error($err);
				$result = false;
			}
			break;*/
	}
	
	/**
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
	 */
	function bhaa_em_event_output_placeholder($result, $EM_Event, $placeholder) {
		//error_log('bhaa_em_event_output_placeholder('.$placeholder.')='.$result);
		switch( $placeholder ){
			case '#_BHAARACERESULTS':
				$result = 'BHAA_RACE_RESULTS';
				break;
			case '#_BHAATEAMRESULTS':
				$result = 'BHAA_TEAM_RESULTS';
				break;
			case '#_BHAASTANDARDS':
				$result = 'This a custom BHAA STANDARDS';
				break;
		}
		//error_log('bhaa_em_event_output_placeholder('.$placeholder.')='.$result);
		return $result;
	}
	
	/**
	 * Add a custom placeholder to handle the BHAA custom email generation.
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
	 * 
	 * em_event_output_placeholder
	 */
	function bhaa_em_booking_output_placeholder($replace, $EM_Booking, $result){
		global $wp_query, $wp_rewrite;
		switch( $result )
		{
			case '#_BHAAID':
				$replace = $EM_Booking->get_person()->ID;
				break;
				// 			case '#_BHAACOMPANYNAME':
				// 				$replace = get_user_meta(7713,'bhaa_runner_company',true);
				// // 				error_log('#_BHAACOMPANY_NAME');
				// // 				$companyid = get_user_meta($EM_Booking->get_person()->ID,'bhaa_runner_company',true);
				// // 				error_log('#_BHAA_COMPANY_NAME '.$companyid);
				// // //				$replace = $companyid.'';
				// //  				$args = array('p'=>$companyid, 'post_type'=>'house', 'limit'=> '1');
				// //  				$loop = new WP_Query($args);
				// //  				$loop->the_post();
				// //  				$replace = $companyid.'-'.the_title();
				// 				break;
			case '#_BHAATICKETS':
				ob_start();
				em_locate_template('emails/bhaatickets.php', true, array('EM_Booking'=>$EM_Booking));
				$replace = ob_get_clean();
				$replace = $EM_Booking->output($replace);
				break;
		}
		return $replace;
	}
	
	// em_booking_form_custom
	function bhaa_em_booking_form_after_user_details($EM_Event) {
	}
	
	function bhaa_em_booking_form_footer($EM_Event) {
		echo '<p>Once you have filled in the form please hit the "Realex Payment" image below and you will be redirected to a secure website where your credit card details will be taken.</p>';
	}
	
	function bhaa_em_booking_form_before_tickets() {
		echo '<p>Existing BHAA members should login first to ensure they can access the discounted rates and their renewal is linked to the correct BHAA ID.
				New runner should just fill out the form below and a new account will be created for you.</p>';
	}
	
	/**
	 * Determine which tickets to display to which users.
	 *
	 * http://wordpress.org/support/topic/plugin-events-manager-conditional-tickets
	 * http://wordpress.org/support/topic/plugin-events-manager-different-tickets-based-on-role
	 * @param unknown_type $result
	 * @param unknown_type $EM_Ticket
	 * @return boolean
	 */
	function bhaa_em_ticket_is_available($result, $EM_Ticket)
	{
		if (current_user_can( strtolower('administrator') )) {
			return true;
		}
	
		if ( $EM_Ticket->ticket_name == Event::DAY_MEMBER_TICKET)
		{
			//if you are an ANNUAL MEMBER then this ticket will NOT show up
			if(!is_user_logged_in())
				return true;
			else if(is_user_logged_in() && get_user_meta(get_current_user_id(),'bhaa_runner_status',true)!='M' )
				return true;
			else
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == Event::BHAA_MEMBER_TICKET)
		{
			//if you are an ANNUAL MEMBER then you can see this ticket
			if(is_user_logged_in() && get_user_meta(get_current_user_id(),'bhaa_runner_status',true)=='M' )
				return true;
			else
				// day member or renewal
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == Event::ANNUAL_MEMBERSHIP)
		{
			// TODO if they are a
			return true;
		}
	}
}
?>