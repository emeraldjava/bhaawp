<?php
/**
 * Handle the BHAA specific Event Manager plugin customisations
 * @author oconnellp
 */
class Events_Manager {
	
	const ANNUAL_MEMBERSHIP = 'Annual Membership';
	const DAY_MEMBER_TICKET = 'Day Member Ticket';
	const BHAA_MEMBER_TICKET = 'BHAA Member Ticket';
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		add_filter('em_event_output_placeholder',array($this,'bhaa_em_event_output_placeholder'),2,4);
		add_filter('em_booking_output_placeholder',array($this,'bhaa_em_booking_output_placeholder'),1,3);

		add_filter('em_ticket_is_available',array($this,'bhaa_em_ticket_is_available'),10,2);
		add_filter('em_bookings_get_tickets',array($this,'bhaa_em_add_default_tickets'),1,2);
		
		// http://snippets.webaware.com.au/snippets/events-manager-pro-and-required-user-fields/
		add_filter('emp_form_validate_field',array($this,'bhaa_emp_form_validate_field'),10,4);
		
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
		
		//add_filters('em_event_output_placeholder',array($this,'bhaa_em_event_output_placeholder'),4,3);
		//add_filter('em_event_output',array($this,'bhaa_em_event_output'),4,3);
	}
	
	function bhaa_emp_form_validate_field($result, $field, $value, $EM_Form) {
	    // if field has validation error and user is a user admin, ignore error
	    if ($field['fieldid']==Runner::BHAA_RUNNER_COMPANY && $field['type']=='select') {
	    	if($value==''|$value==0)
	    		$value=1;
	    	
	    	global $current_user;
	    	error_log('bhaa_emp_form_validate_field('.$current_user->ID.') type[select] bhaa_runner_company='.$value);
	    	update_user_meta($current_user->ID,Runner::BHAA_RUNNER_COMPANY,$value);
	    	$result = false;
	        array_pop($EM_Form->errors);
	    }
	    else if ($field['fieldid']==Runner::BHAA_RUNNER_COMPANY && $field['type']==Runner::BHAA_RUNNER_COMPANY) {
	    	if($value==''|$value==0)
	    		$value=1;
	    	error_log('bhaa_emp_form_validate_field type[Runner::BHAA_RUNNER_COMPANY] Runner::BHAA_RUNNER_COMPANY='.$value.' RESULT '.print_r($result));
	    	//error_log(print_r($field,true));
	    	$result = false;
	        array_pop($EM_Form->errors);
	    }
	    return $result;
	}
	
	/**
	 * Set up default tickets for each event.
	 * 
	 * http://eventsmanagerpro.com/support/requests/editable-default-ticket/
	 * http://eventsmanagerpro.com/support/questions/default-ticket-being-marked-as-unavailable/
	 */
	function bhaa_em_add_default_tickets($tickets,$EM_Bookings){
		if ( sizeof($tickets->tickets) == 0 ) {
			$ticket_data = array();
			$ticket_data[0] = array(
				'ticket_name'=>'BHAA Member Ticket',
				'ticket_description'=>'BHAA Members runner for 10e',
				'ticket_spaces'=>400,
				'ticket_price'=>10,
				'ticket_start' => $EM_Bookings->get_event()->event_start_date,
				'ticket_end' => $EM_Bookings->get_event()->event_end_date,
				'ticket_max' => 1,
				'ticket_min' => 1,
				'ticket_members' => 0,
				'ticket_required' => 0,
				'ticket_members_roles' => null,
				'ticket_guests' => 0
			);
			
 			$ticket_data[1] = array(
				'ticket_name'=>'Day Member Ticket',
 				'ticket_description'=>'Day Member Ticket',
 				'ticket_spaces'=>400,
				'ticket_price'=>15,
 				'ticket_start' => $EM_Bookings->get_event()->event_start_date,
				'ticket_end' => $EM_Bookings->get_event()->event_end_date,
 				'ticket_max' => 1,
 				'ticket_min' => 1,
 				'ticket_members' => 0,
 				'ticket_required' => 0,
 				'ticket_members_roles' => null,
 				'ticket_guests' => 0
			); 
			if ( is_array($tickets->tickets) )unset($tickets->tickets);
			foreach ($ticket_data as $ticket){
				$EM_Ticket = new EM_Ticket($ticket);
				$tickets->tickets[] = $EM_Ticket;
			}
			// set the Booking Cut off Date to 1 day before hand
			$EM_Bookings->get_event()->event_rsvp_date = date('Y-m-d',strtotime( $EM_Bookings->get_event()->event_start_date . ' -1 day' ) );
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
				'id' => $field['fieldid'],
				'name' => $field['fieldid'],
				'echo' => 1,
				'post_type' => 'house',
				'exclude' => $csv
			);
				
			global $current_user;
			$selected = get_user_meta($current_user->ID,Runner::BHAA_RUNNER_COMPANY,true);	
			// set the correct defaults for new or existing user
			if($selected==0||$selected=='') {
				$args = array_merge( $args, array( 'show_option_none' => 'Please select a company' ) );
				$args = array_merge( $args, array( 'option_none_value' => '1' ) );
			} else {
				$args = array_merge( $args, array( 'selected' => $selected ) );
			}
			wp_dropdown_pages($args);
		}
		else
			return $html;
	}
		
	/**
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
	 */
	function bhaa_em_event_output_placeholder($result, $EM_Event, $placeholder) {
		//error_log(sprintf('bhaa_em_event_output_placeholder(%s)',$placeholder));
		if($placeholder=='#_BOOKINGFORM') {
			if(get_option('bhaa_bookings_enabled')==0){
				// booking disabled
				$result = do_shortcode('[av_notification title="Note" color="orange" border="" custom_bg="#444444" custom_font="#ffffff" size="large" icon_select="no" font="entypo-fontello"]'.
					'The online registration form is currently disabled while we prepare for the next BHAA event. Registration is available on the day at the next event. See you there.'.
					'[/av_notification]');
			}
			return $result;
		}
		else if(!(substr( $placeholder, 2, 4)=="BHAA"))
			return $result;
		else {
			//error_log('bhaa_em_event_output_placeholder('.$placeholder.')='.$EM_Event->post_id.' '.substr( $placeholder, 2, 4 ));
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
	function bhaa_em_booking_output_placeholder($replace, $EM_Booking, $result) {
		
		global $wp_query, $wp_rewrite;
		switch( $result )
		{
			case '#_BHAAID':
				$replace = $EM_Booking->get_person()->ID;
				//return $replace;
				break;
			case '#_BHAATICKETS':
				//error_log('bhaa_em_booking_output_placeholder() '.$replace.' '.$result);
				//$header = '#_EVENTNAME : #_BOOKINGTICKETNAME';
				
				/*$eventDetails = false;
				$membershipDetails = false;
				
				foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking) {
					$booking = $EM_Ticket_Booking->get_ticket();
					//error_log('bhaa_em_booking_output_placeholder() '.print_r($booking,true));
					if($booking->name=='Annual Membership') {
						$membershipDetails = true;
					} elseif($booking->name=='BHAA Member Ticket') {
						$eventDetails = true;
					} else {
						$eventDetails = true;
					}
					break;
				}*/

				//error_log(var_dump($EM_Booking,true));
				//$runner = new Runner($EM_Booking->person_id);
				error_log("booking "+$EM_Booking->booking_id.' for runner '.$EM_Booking->person_id.' at event '.$EM_Booking->event_id);
				
				global $wp_query;
				$wp_query->set('id',$EM_Booking->person_id);
				$wp_query->set('booking_id',$EM_Booking->booking_id);
				
				$page = get_page_by_title('email-runner-booking');
				$email = apply_filters('the_content', $page->post_content);
				//echo $content;
				
				/*
				$email = Bhaa_Mustache::get_instance()->loadTemplate('runner-booking-email')->render(
					array(
						'header' => $header,
						'eventDetails' => $eventDetails,
						'membershipDetails' => $membershipDetails,
						'user'=>$runner,
						'event'=>$EM_Booking->get_event(),
						'sent_time'=>date('h:i:s d/m/Y', time())
				));
				*/
				//error_log("sending email to ".get_query_var('id'));
				//error_log("email ".$email);
				//$email = Bhaa_Mustache::get_instance()->inlineCssStyles($email);
				$message = $EM_Booking->output($email);
				//error_log("message ".$message);
				$html = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
					<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">'.
					$message.
					'</body></html>';
				//error_log($html);
				$replace = $html;
				break;
		}
		return $replace;
	}
	
	function bhaa_booking_details_shortcode($atts = null,$content = null){
		//error_log('bhaa_booking_details_shortcode '.get_query_var('booking_id').' POST '.$_POST['booking_id']);
		$EM_Booking = new EM_Booking(get_query_var('booking_id'));
		return '<p>booking '+$EM_Booking->booking_id.' for runner '.$EM_Booking->person_id.' at event '.$EM_Booking->event_id.'</p>'.$content;
	}
	
	// bhaa_booking_annual_membership
	function bhaa_booking_annual_membership_shortcode($atts = null,$content = null){
		$EM_Booking = new EM_Booking(get_query_var('booking_id'));
		$EM_Ticket = $EM_Booking->get_tickets()->get_first();
		if(trim($EM_Ticket->ticket_name)=="Annual Membership") {
			return 'IS a '.$EM_Ticket->ticket_name.'.';
		} else {
			return 'NOT an annual membership, is a '.trim($EM_Ticket->ticket_name).'.';
		}
	}
	
	// bhaa_booking_member_ticket
	function bhaa_booking_member_ticket_shortcode($atts = null,$content = null){
		$EM_Booking = new EM_Booking(get_query_var('booking_id'));
		$EM_Ticket = $EM_Booking->get_tickets()->get_first();
		if(trim($EM_Ticket->ticket_name)=="BHAA Member Ticket") {
			return 'IS a '.$EM_Ticket->ticket_name.'.';
		} else {
			return 'NOT a BHAA Member Ticket, is a '.trim($EM_Ticket->ticket_name).'.';
		}
	}
	
	// bhaa_booking_day_ticket
	function bhaa_booking_day_ticket_shortcode($atts = null,$content = null){
		$EM_Booking = new EM_Booking(get_query_var('booking_id'));
		$EM_Ticket = $EM_Booking->get_tickets()->get_first();
		if(trim($EM_Ticket->ticket_name)=="Day Member Ticket") {
			return 'IS a '.$EM_Ticket->ticket_name.'.';
		} else {
			return 'NOT a Day Member Ticket, is a '.trim($EM_Ticket->ticket_name).'.';
		}
	}
	
	// em_booking_form_custom
	function bhaa_em_booking_form_after_user_details($EM_Event) {
	}
	
	function bhaa_em_booking_form_footer($EM_Event) {
		echo '<p>Once you have filled in the form please hit the "Realex Payment" image below and you will be redirected to a secure website where your credit card details will be taken.</p>';
	}
	
	function bhaa_em_booking_form_before_tickets() {
		if(!is_user_logged_in()) {
			echo '<p>Existing BHAA members should first <a href="'.wp_login_url(get_permalink()).'" title="Login">Login</a> to ensure they can access the discounted rates and their ticket is linked to the correct BHAA ID. New runners should just fill out the form below and a new account will be created for you.';
		} else {
			global $current_user;
			echo '<b>Welcome '.$current_user->first_name.' to the ticket booking page</b>';
		}
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
