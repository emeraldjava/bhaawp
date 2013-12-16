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
		add_filter('emp_forms_output_field_input',array($this,'bhaa_emp_forms_output_field_input'),2,3);
	}
	
	/**
	 * http://eventsmanagerpro.com/support/questions/custom-form-select-options-using-wp_dropdown_pages/
	 */
	function bhaa_emp_forms_output_field_input($html, $form, $field) {
		//error_log('bhaa_emp_forms_output_field_input() field '.print_r($field,true));
		
		if($field['type']=='house' && $field['fieldid']=='house') {
			
			error_log('bhaa_emp_forms_output_field_input() field '.print_r($field,true));
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
				
			global $current_user, $user_id;
			$selected = get_user_meta($user_id,'bhaa_runner_company',true);
			// set the correct defaults for new or existing user
			if($selected==0) {
				$args = array_merge( $args, array( 'show_option_none' => 'Please select a company' ) );
				$args = array_merge( $args, array( 'option_none_value' => '1' ) );
			} else {
				$args = array_merge( $args, array( 'selected' => $selected ) );
			}
			error_log('wp_dropdown_pages args '.print_r($args,true));
			wp_dropdown_pages($args);
		}
		else
			return $html;
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
	function bhaa_em_booking_output_placeholder($html, $EM_Booking, $result){
		//error_log('bhaa_em_booking_output_placeholder()');
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
				
				$header = '#_EVENTNAME : #_BOOKINGTICKETNAME';
				$eventDetails = false;
				$membershipDetails = false;
				
				foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking) {
					$booking = $EM_Ticket_Booking->get_ticket();
					error_log('bhaa_em_booking_output_placeholder() '.print_r($booking,true));
					if($booking->name=='Annual Membership') {
						$membershipDetails = true;
					} elseif($bookings->name=='BHAA Member Ticket') {
						$eventDetails = true;
					} else {
						$eventDetails = true;
					}
					break;
				}
				
				$options =  array('extension' => '.html');
				$this->mustache = new Mustache_Engine(
					array(
						'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates/email',$options),
						'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates/partials',$options)
					)
				);
				
				$template = $this->mustache->loadTemplate('booking');
				$email = $template->render(
					array(
						'header' => $header,
						'eventDetails' => $eventDetails,
						'membershipDetails' => $membershipDetails
				));
				//error_log($email);
				//ob_start();
				//em_locate_template('emails/bhaatickets.php', true, array('EM_Booking'=>$EM_Booking));
				//$replace = ob_get_clean();
				$html = $EM_Booking->output($email);
				//error_log($html);
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
	function bhaa_em_ticket_is_available($result, $EM_Ticket)
	{
		if (current_user_can( strtolower('administrator') )) {
			return true;
		}
	
		if ( $EM_Ticket->ticket_name == Event::DAY_MEMBER_TICKET) {
			//if you are an ANNUAL MEMBER then this ticket will NOT show up
			if(!is_user_logged_in())
				return true;
			else if(is_user_logged_in() && get_user_meta(get_current_user_id(),'bhaa_runner_status',true)!='M' )
				return true;
			else
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == Event::BHAA_MEMBER_TICKET) {
			//if you are an ANNUAL MEMBER then you can see this ticket
			if(is_user_logged_in() && get_user_meta(get_current_user_id(),'bhaa_runner_status',true)=='M' )
				return true;
			else
				// day member or renewal
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == Event::ANNUAL_MEMBERSHIP) {
			// TODO if they are a
			return true;
		}
	}
}
?>