<?php
/**
 * Handle the BHAA specific Event Manager plugin customisations
 * @author oconnellp
 */
class BHAAEventManager {
	
	function __construct() {
		add_filter('em_booking_output_placeholder',array($this,'bhaa_em_booking_output_placeholder'),1,3);
		add_filter('em_ticket_is_available',array($this,'bhaa_em_ticket_is_available'), 10, 2);
		
		// em_booking_form_before_tickets
		// em_booking_form_after_tickets
		// em_booking_form_before_user_details
		// em_booking_form_after_user_details
		// em_booking_form_footer
		add_action('em_booking_form_after_user_details',array($this,'bhaa_em_booking_form_after_user_details'),9,2);
		add_action('em_booking_form_footer',array($this,'bhaa_em_booking_form_footer'),9,2);
	}
	
	/**
	 * Add a custom placeholder to handle the BHAA custom email generation.
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
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
	function bhaa_em_booking_form_after_user_details($EM_Event){
		//if( $EM_Event->can_manage('manage_bookings','manage_others_bookings') )
		//{
		$args = array (
				'id' => 'bhaa_runner_house',
				'name' => 'bhaa_runner_house',
				'echo' => 0,
				'selected' => 1119,
				'post_type' => 'house'
		);
		//error_log('bhaa_em_booking_form_after_user_details');
		echo '<p class="input-bhaa_runner_house input-user-field">
		<label for="bhaa_runner_house">
		<span class="form-tip" oldtitle="Use the search box to find the company you work for, if the name is not there email us and we can add it." title="">
		Company  <span class="em-form-required">*</span></span>
		</label>
		'. wp_dropdown_pages($args).'</p>';
	}
	
	function bhaa_em_booking_form_footer($EM_Event){
		echo '<p>Please hit the "Pay with Realex Button" below and you will be redirected and asked to enter your credit card details on the secure server</p>';
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