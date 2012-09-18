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
 * -- show bhaa logo on the login/registration page
 * http://www.paulund.co.uk/change-wordpress-login-logo-without-a-plugin
 */
class Runner
{	
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	function __construct()
	{
		// registration actions
// 		add_action('register_form',array(&$this,'bhaa_registration_fields')); // day details
// 		add_action('user_register',array(&$this,'register_bhaa_fields'));

// 		// remove the default filter
// 		remove_filter('authenticate',array(&$this,'wp_authenticate_username_password'), 20, 3);
// 		// add custom filter
// 		add_filter('authenticate',array(&$this,'bhaa_authenticate_username_password'), 20, 3);
		
// 		// user profile stuff
// 		add_action('show_user_profile',array(&$this,'add_bhaa_profile_fields'));
// 		add_action('edit_user_profile',array(&$this,'add_bhaa_profile_fields'));
		
// 		add_action('personal_options_update',array(&$this,'save_bhaa_user_profile_fields'));
// 		add_action('edit_user_profile_update',array(&$this,'save_bhaa_user_profile_fields'));
	}
	
	function Runner()
	{
		$this->__construct();
	}
	
	function bhaa_authenticate_username_password( $user, $username, $password ) {
	
		// If an email address is entered in the username box,
		// then look up the matching username and authenticate as per normal, using that.
		if ( ! empty( $username ) )
			$user = get_user_by( 'email', $username );
	
		if ( isset( $user->user_login, $user ) )
			$username = $user->user_login;
	
		// using the username found when looking up via email
		return wp_authenticate_username_password( NULL, $username, $password );
	}
	
	function bhaa_registration_fields()
	{
		echo '<p><label>First Name<br />';
		echo '<input id="user_email" class="input validate[required]" type="text" tabindex="20" size="20" value=""/></label></p>';
		
		echo '<p><label>Last Name<br />';
		echo '<input id="user_email" class="input validate[required]" type="text" tabindex="20" size="20" value=""/></label></p>';
		
		echo '<p><label for="user_login">Date Of Birth<br />';
		echo '<input type="text" class="input validate[custom[date]]" type="text" tabindex="20" size="20" name="bhaa_runner_dateofbirth" id="bhaa_runner_dateofbirth"/></label></p>';		
		
		echo '<p><label for="bhaa_runner_gender">Gender<br />';
		echo '<input type="radio" name="gender" id="bhaa_runner_gender_male" class="validate[required]" value="Male">Male<br>';
		echo '<input type="radio" name="gender" id="bhaa_runner_gender_female" class="validate[required]" value="Female">Female<br></p>';

		echo '<p><label>Company<br/>';
		echo '<select name="bhaa_runner_company" id="bhaa_runner_company" class="validate[required]">';
		echo '<option value="Other">Other</option>';
		echo '</select>';
		
		$nonce = wp_create_nonce('bhaa-nonce');
		echo '<input type="hidden" id="pf_nonce" name="pf_nonce" value="'.$nonce.'">';
		echo '<input type="hidden" name="_wp_http_referer" value="/profile/register/">';
		
	}
	
	function register_bhaa_fields($user_id, $password="", $meta=array()) 
	{
		// Gotta put all the info into an array
		$userdata = array();
		$userdata['ID'] = $user_id;
		
		// First name
		$userdata['first_name'] = $_POST['first'];
		
		// Last Name
		$userdata['last_name'] = $_POST['last'];
		
		// Enters into DB
		wp_update_user($userdata);
		
		// This is for custom meta data "gender" is the custom key and M is the value
		// update_usermeta($user_id, 'gender','M');
		update_user_meta( $user_id, 'bhaa_runner_dateofbirth', $_POST['bhaa_runner_dateofbirth']);
		update_user_meta( $user_id, 'bhaa_runner_gender', $_POST['bhaa_runner_gender']);
		update_user_meta( $user_id, 'bhaa_runner_company', $_POST['bhaa_runner_company']);
		update_user_meta( $user_id, BHAA_RUNNER_STATUS, DAY);
	}
	
	
// 	function day_runner_form()
// 	{
// 		echo "display the subset of day runner specific fields";
// 		$this->add_bhaa_profile_fields();
// 	}
	
// 	function new_runner_form()
// 	{
// 		echo "display the full set of new runner specific fields";
// 		$this->add_bhaa_profile_fields();
// 	}
	
	// http://bavotasan.com/2009/adding-extra-fields-to-the-wordpress-user-profile/
	function add_bhaa_profile_fields($user) {
			
		$bhaa_runner_address1 = get_user_meta($user->ID,Runner::BHAA_RUNNER_ADDRESS1,true);
		echo '<h3>BHAA Data</h3>';
		echo '<table class="form-table">
		<tr>
		<th><label for="address">Address 1</label></th>
		<td>
		<input type="text" name="bhaa_runner_address1" id="bhaa_runner_address1" value="'.$bhaa_runner_address1.'" class="regular-text" /><br />
		<span class="description">Please enter your address.</span>
		</td>
		</table>';
		
		echo '<table class="form-table">
		<tr>
		<th><label for="bhaa_runner_address2">Address 2</label></th>
		<td>
		<input type="text" name="bhaa_runner_address2" id="bhaa_runner_address2" value="'.get_user_meta($user->ID,Runner::BHAA_RUNNER_ADDRESS2,true).'" class="regular-text" /><br />
		<span class="description">Please enter your address.</span>
		</td>
		</table>';
		
		echo '<table class="form-table">
		<tr>
		<th><label for="bhaa_runner_address2">Address 3</label></th>
		<td>
		<input type="text" name="bhaa_runner_address3" id="bhaa_runner_address3" value="'.get_user_meta($user->ID,Runner::BHAA_RUNNER_ADDRESS3,true).'" class="regular-text" /><br />
		<span class="description">Please enter your address.</span>
		</td>
		</table>';
		
		echo '<p>Date: <input type="text" id="datepicker"></p>';
		
		// add extra profile fields to user edit page
	}
	
	function save_bhaa_user_profile_fields( $user_id ) {
	
		if ( !current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
	
		update_user_meta( $user_id, Runner::BHAA_RUNNER_ADDRESS1, $_POST[Runner::BHAA_RUNNER_ADDRESS1] );
		update_user_meta( $user_id, Runner::BHAA_RUNNER_ADDRESS2, $_POST[Runner::BHAA_RUNNER_ADDRESS2] );
		update_user_meta( $user_id, Runner::BHAA_RUNNER_ADDRESS3, $_POST[Runner::BHAA_RUNNER_ADDRESS3] );
		//update_user_meta( $user_id, 'province', $_POST['province'] );
		//update_user_meta( $user_id, 'postalcode', $_POST['postalcode'] );
	}
}
?>