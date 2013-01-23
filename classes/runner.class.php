<?php
/**
 * The runner class will handle user registration and editing of BHAA specific meta-data fields.
 * 
 * users will use email to login
 * username will be firstname.surname
 * 
 * @author oconnellp
 * 
 * -- add bhaa fields to registration
 * http://wordpress.org/support/topic/howto-custom-registration-fields
 * http://www.tutorialstag.com/create-custom-wordpress-registration-page.html
 *
 * -- use email as the login (we'll set the username based on first last name
 * http://wordpress.stackexchange.com/questions/51678/how-to-login-with-email-only-no-username
 * 
 * http://trepmal.com/action_hook/register_form/
 * http://www.rarescosma.com/2011/04/wordpress-hooks-user_register/
 * 
 * -- show bhaa logo on the login/registration page
 * http://www.paulund.co.uk/change-wordpress-login-logo-without-a-plugin
 * 
 * -- admin user columns
 * http://w4dev.com/wp/add-last-login-time-in-wp-admin-users-column/
 * 
 * http://stackoverflow.com/questions/9792418/how-do-i-select-user-id-first-and-last-name-directly-from-wordpress-database
 
 http://mattvarone.com/wordpress/list-users-with-wp_user_query/ 
 http://codex.wordpress.org/Class_Reference/WP_User_Query
 http://wp.smashingmagazine.com/2012/06/05/front-end-author-listing-user-search-wordpress/
 
 CIMY
 http://wordpress.org/extend/plugins/cimy-user-extra-fields/developers/
 http://blog.ftwr.co.uk/archives/2009/07/19/adding-extra-user-meta-fields/
 */
class Runner
{	
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_FIRSTNAME = 'bhaa_runner_firstname';
	const BHAA_RUNNER_LASTNAME = 'bhaa_runner_lastname';
	
	const BHAA_RUNNER_DATEOFBIRTH = 'bhaa_runner_dateofbirth';
	const BHAA_RUNNER_COMPANY = 'bhaa_runner_company';
	const BHAA_RUNNER_NEWSLETTER = 'bhaa_runner_newsletter';
	const BHAA_RUNNER_TEXTALERT = 'bhaa_runner_textalert';
	const BHAA_RUNNER_MOBILEPHONE = 'bhaa_runner_mobilephone';
	const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	const BHAA_RUNNER_TERMS_AND_CONDITIONS = 'bhaa_runner_terms_and_conditions';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	const BHAA_RUNNER_GENDER = 'bhaa_runner_gender';
	const MAN = 'M';
	const WOMAN = 'W';
	
	const BHAA_RUNNER_STANDARD = 'bhaa_runner_standard';
	
// 	function em_user_action_links( $actions, $user ){
// 		if ( !is_network_admin() && current_user_can( 'manage_others_bookings' ) ){
// 			if( get_option('dbem_edit_bookings_page') && (!is_admin() || !empty($_REQUEST['is_public'])) ){
// 				$my_bookings_page = get_permalink(get_option('dbem_edit_bookings_page'));
// 				$bookings_link = em_add_get_params($my_bookings_page, array('person_id'=>$user->ID), false);
// 			}else{
// 				$bookings_link = EM_ADMIN_URL. "&page=events-manager-bookings&person_id=".$user->ID;
// 			}
// 			$actions['bookings'] = "<a href='$bookings_link'>" . __( 'Bookings','dbem' ) . "</a>";
// 		}
// 		return $actions;
// 	}
// 	//add_filter('user_row_actions','em_user_action_links',10,2);
}
?>