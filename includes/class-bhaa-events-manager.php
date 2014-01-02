<?php
/**
 * Handle the BHAA specific Event Manager plugin customisations
 * @author oconnellp
 */
class Events_Manager {
	
	const ANNUAL_MEMBERSHIP = 'Annual Membership';
	const DAY_MEMBER_TICKET = 'Day Member Ticket';
	const BHAA_MEMBER_TICKET = 'BHAA Member Ticket';
	
	function __construct() {
		add_filter('em_event_output_placeholder',array($this,'bhaa_em_event_output_placeholder'),2,4);
		add_filter('em_booking_output_placeholder',array($this,'bhaa_em_booking_output_placeholder'),1,3);

		add_filter('em_ticket_is_available',array($this,'bhaa_em_ticket_is_available'),10,2);
		add_filter('em_bookings_get_tickets',array($this,'bhaa_em_add_default_tickets'),1,2);
		
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
		add_filter('emp_forms_output_field_input',array($this,'bhaa_emp_forms_output_field_input'),2,3);
	}
	
	/**
	 * http://eventsmanagerpro.com/support/requests/editable-default-ticket/
	 * $BHAA_tickets[0] = new EM_Ticket();
		$BHAA_tickets[0]->ticket_name = "BHAA Member Ticket";
		//$BHAA_tickets[0]->ticket_description = "Run for 10";
		$BHAA_tickets[0]->ticket_price = 10;//$BHAA_Payments[day];
		$BHAA_tickets[0]->ticket_min = 1;
		$BHAA_tickets[0]->ticket_max = 1;
		$BHAA_tickets[0]->ticket_start = $EM_Event->event_start_date;
		$BHAA_tickets[0]->ticket_end = $EM_Event->event_end_date;
		$BHAA_tickets[0]->ticket_spaces_limit = 200;
		
	 * @param unknown $tickets
	 * @param unknown $EM_Bookings
	 * @return unknown
	 */
	function bhaa_em_add_default_tickets($tickets,$EM_Bookings){
		//error_log('bhaa_em_add_default_tickets :'.$tickets->event_id.': '.sizeof($tickets->tickets));
		if ( sizeof($tickets->tickets) == 0 ) {
			$ticket_data = array();
			$ticket_data[0] = array(
				'ticket_name'=>'BHAA Member Ticket',
				'ticket_description'=>'BHAA Members runner for 10e','ticket_spaces'=>400,
				'ticket_price'=>10,'ticket_min'=>1,'ticket_max'=>1,'ticket_spaces_limit'=>400,
				'ticket_start' => $EM_Bookings->get_event()->event_start_date,
				'ticket_end' => $EM_Bookings->get_event()->event_end_date
			);
			
 			$ticket_data[1] = array(
				'ticket_name'=>'Day Member Ticket','ticket_description'=>'Day Member Ticket','ticket_spaces'=>400,
				'ticket_price'=>15,'ticket_min'=>1,'ticket_max'=>1,'ticket_spaces_limit'=>400,
				'ticket_start' => $EM_Bookings->get_event()->event_start_date,
				'ticket_end' => $EM_Bookings->get_event()->event_end_date
			); 
			if ( is_array($tickets->tickets) )unset($tickets->tickets);
			foreach ($ticket_data as $ticket){
				$EM_Ticket = new EM_Ticket($ticket);
				$tickets->tickets[] = $EM_Ticket;
			}
			// set the Booking Cut off Date to 1 day before hand
			$EM_Bookings->get_event()->event_rsvp_date = date('Y-m-d',
				strtotime( $EM_Bookings->get_event()->event_start_date . ' -1 day' ) );
			$EM_Bookings->get_event()->event_rsvp_time = '15:00';
		}
		return $tickets;
	}

	
	/**
	 * http://eventsmanagerpro.com/support/questions/custom-form-select-options-using-wp_dropdown_pages/
	 */
	function bhaa_emp_forms_output_field_input($html, $form, $field) {
		//error_log('bhaa_emp_forms_output_field_input() field '.print_r($field,true));
		
		if($field['type']=='select' && $field['fieldid']==Runner::BHAA_RUNNER_COMPANY) {
			
			//error_log('bhaa_emp_forms_output_field_input() field '.print_r($field,true));
			$sectorTeamQuery = new WP_Query(
				array(
					'post_type' => 'house',
					'order'		=> 'ASC',
					'post_status' => 'publish',
					'orderby' 	=> 'title',
					'nopaging' => true,
					'tax_query'	=> array(
						array(
							'taxonomy'  => 'teamtype',
							'field'     => 'slug',
							'terms'     => 'sector', // exclude house posts in the sectorteam custom teamtype taxonomy
							'operator'  => 'IN')
					)
				)
			);
			$csv = implode(',',array_map(function($val){return $val->ID;},$sectorTeamQuery->posts) );
			$args = array (
				'id' => 'house',//$field['name'],//$field_name,
				'name' => 'house',//$field['name'],//$field_name,
				'echo' => 1,
				'post_type' => 'house',
				'exclude' => $csv
			);
				
			global $current_user;
			$selected = get_user_meta($current_user->ID,Runner::BHAA_RUNNER_COMPANY,true);			
			// set the correct defaults for new or existing user
			if($selected==0) {
				$args = array_merge( $args, array( 'show_option_none' => 'Please select a company' ) );
				$args = array_merge( $args, array( 'option_none_value' => '1' ) );
			} else {
				$args = array_merge( $args, array( 'selected' => $selected ) );
			}
			//error_log('wp_dropdown_pages args '.print_r($args,true));
			wp_dropdown_pages($args);
		}
		else
			return $html;
	}
		
	/**
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
	 */
	function bhaa_em_event_output_placeholder($result, $EM_Event, $placeholder) {
		if(!(substr( $placeholder, 2, 4)=="BHAA"))
			return $result;
		else {
			error_log('bhaa_em_event_output_placeholder('.$placeholder.')='.$EM_Event->post_id.' '.substr( $placeholder, 2, 4 ));
			$event = new Event($EM_Event->post_id);
			switch( $placeholder ) {
				case '#_BHAARACERESULTS':
					$result = $event->getIndividualResultsTable();
					break;
				case '#_BHAATEAMRESULTS':
					$result = $event->getTeamResultsTable();
					break;
				case '#_BHAASTANDARDS':
					$result = StandardCalculator::get_instance()->getEventStandardTable($EM_Event->post_id);
					break;
			}
			return $result;
		}
	}
	
	/**
	 * Add a custom placeholder to handle the BHAA custom email generation.
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
	 * 
	 * em_event_output_placeholder
	 */
	function bhaa_em_booking_output_placeholder($html, $EM_Booking, $result) {
		//error_log('bhaa_em_booking_output_placeholder()');
		global $wp_query, $wp_rewrite;
		switch( $result )
		{
			case '#_BHAAID':
				$replace = $EM_Booking->get_person()->ID;
				break;
			case '#_BHAATICKETS':
				$header = '#_EVENTNAME : #_BOOKINGTICKETNAME';
				$eventDetails = false;
				$membershipDetails = false;
				
				foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking) {
					$booking = $EM_Ticket_Booking->get_ticket();
					//error_log('bhaa_em_booking_output_placeholder() '.print_r($booking,true));
					if($booking->name=='Annual Membership 2014') {
						$membershipDetails = true;
					} elseif($booking->name=='BHAA Member Ticket') {
						$eventDetails = true;
					} else {
						$eventDetails = true;
					}
					break;
				}

				$runner = new Runner($EM_Booking->get_person()->ID);
				$email = Bhaa_Mustache::get_instance()->loadTemplate('runner-booking-email')->render(
					array(
						'header' => $header,
						'eventDetails' => $eventDetails,
						'membershipDetails' => $membershipDetails,
						'user'=>$runner,
						'event'=>$EM_Booking->get_event(),
						'sent_time'=>date('h:i:s d/m/Y', time())
				));
				$email = Bhaa_Mustache::get_instance()->inlineCssStyles($email);
				$html = $EM_Booking->output($email);
				break;
		}
		return $html;
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
	function bhaa_em_ticket_is_available($result, $EM_Ticket) {
		
		if (current_user_can( strtolower('administrator') )) {
			return true;
		}
	
		if ( $EM_Ticket->ticket_name == Events_Manager::DAY_MEMBER_TICKET) {
			//if you are an ANNUAL MEMBER then this ticket will NOT show up
			if(!is_user_logged_in())
				return true;
			else if(is_user_logged_in() && get_user_meta(get_current_user_id(),'bhaa_runner_status',true)!='M' )
				return true;
			else
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == Events_Manager::BHAA_MEMBER_TICKET) {
			//if you are an ANNUAL MEMBER then you can see this ticket
			if(is_user_logged_in() && get_user_meta(get_current_user_id(),'bhaa_runner_status',true)=='M' )
				return true;
			else
				// day member or renewal
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == Events_Manager::ANNUAL_MEMBERSHIP) {
			// TODO if they are a
			return true;
		}
	}
}
?>