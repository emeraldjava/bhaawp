<?php
// http://pythoughts.com/how-to-hide-that-you-use-wordpress/
remove_action('wp_head','wp_generator');

// // http://www.wprecipes.com/customize-wordpress-login-logo-without-a-plugin
// function bhaa_login_logo() {
// 	echo '<style type="text/css">
// 	h1 a { 
// 	height:350px;
// 	margin-left:10px;
// 	margin-top:10px;
// 	width:145px;
// 	background-image:url('.content_url().'/themes/bhaatwentytwelve/images/logo.png) !important; }
// 	</style>';
// }
// add_action('login_head', 'bhaa_login_logo');

// // http://wpmu.org/how-to-simplify-wordpress-profiles-by-removing-personal-options/
// function hide_personal_options(){
// 	echo "\n" . '<script type="text/javascript">
// 	jQuery(document).ready(function($)
// 	{ 
// 		$(\'form#your-profile > h3:first\').hide(); 
// 		$(\'form#your-profile > table:first\').hide(); 
// 		$(\'form#your-profile\').show(); 
// 	});
// 	</script>' . "\n";
// }
// add_action('admin_head','hide_personal_options');

// // http://codex.wordpress.org/Plugin_API/Filter_Reference#Author_and_User_Filters
// // http://wpmu.org/remove-aim-yahoo-and-jabber-fields-from-the-wordpress-profile-page/
// function edit_contactmethods( $contactmethods ) {
// 	//$contactmethods['facebook'] = 'Facebook';
// 	//$contactmethods['twitter'] = 'Twitter';
// 	unset($contactmethods['yim']);
// 	unset($contactmethods['aim']);
// 	unset($contactmethods['jabber']);
// 	return $contactmethods;
// }
// add_filter('user_contactmethods','edit_contactmethods',10,1);


/**
 * http://wordpress.org/support/topic/plugin-events-manager-conditional-tickets
 * http://wordpress.org/support/topic/plugin-events-manager-different-tickets-based-on-role
 * @param unknown_type $result
 * @param unknown_type $EM_Ticket
 * @return unknown|boolean
 */
function bhaa_em_ticket_is_available($result, $EM_Ticket) {
	//error_log('bhaa_em_ticket_is_available : '.$EM_Ticket->ticket_name.' '.$result);
	//global $wpdb;
	//$BHAA_Payments = get_option( 'bhaa_payments_default_values', "FALSE" );
	if (current_user_can( strtolower('administrator') )) {
		//return true;
	}
	//echo $EM_Ticket->ticket_name;
	if ( $EM_Ticket->ticket_name == 'Day Member Ticket') {// && !current_user_can( strtolower($BHAA_Payments[annualname]) ) ) {
		//if you are an ANNUAL MEMBER then this ticket will NOT show up
		if(!is_user_logged_in())
			return true;
		else
			return false;
	}
	
	if ( $EM_Ticket->ticket_name == 'BHAA Member Ticket') {// && current_user_can( strtolower($BHAA_Payments[annualname]) ) ) {
		//if you are an ANNUAL MEMBER then you can see this ticket
		if(is_user_logged_in())
			return true;
		else
			return false;
	}
	//else if ( $EM_Ticket->ticket_name != 'BHAA Member Ticket' && $EM_Ticket->ticket_name != 'Day Member Ticket') {
	//	// if ticket is NOT Annual OR Day, then everyone can see
	//	return $result;
	//}
	//error_log('bhaa_em_ticket_is_available : '.$EM_Ticket->ticket_name.' '.$result);
	
	//return false;
}
//add_filter('em_ticket_is_available', 'bhaa_em_ticket_is_available', 10, 2);

?>