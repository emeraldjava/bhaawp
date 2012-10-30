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
 */
class Runner
{	
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_DATEOFBIRTH = 'bhaa_runner_dateofbirth';
	const BHAA_RUNNER_COMPANY = 'bhaa_runner_company';
	const BHAA_RUNNER_NEWSLETTER = 'bhaa_runner_newsletter';
	const BHAA_RUNNER_TEXTALERT = 'bhaa_runner_textalert';
	const BHAA_RUNNER_MOBILEPHONE = 'bhaa_runner_mobilephone';
	const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	const BHAA_RUNNER_GENDER = 'bhaa_runner_gender';
	const MAN = 'M';
	const WOMAN = 'W';
	
	function Runner()
	{
		//require_once( ABSPATH . 'wp-content/plugins/events-manager/classes/em-events.php' );
		
		// extra reg fields
		add_action('register_form',array($this,'bhaa_register_form'));
		// register_post		
		//add_action('user_register',array($this,'bhaa_user_register'));

		//add_filter('wp_nav_menu_items',array($this,'add_loginout_link',10,2));
 		
		// display the membership status
		add_filter('manage_users_columns',array($this,'bhaa_manage_users_columns'));
 		add_filter('manage_users_custom_column',array($this,'bhaa_manage_users_custom_column'), 10, 3 );		

 		register_sidebar(array(
			'name'			=> 'Membership Sidebar',
			'id'            => 'sidebar-membership',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
 		));

 		//add_action('show_user_profile',array(&$this,'add_bhaa_profile_fields'));
	}
	
	function add_loginout_link( $items, $args ) {
		if (is_user_logged_in()) {
			$items .= '<li><a href="'. wp_logout_url() .'">Log Out</a></li>';
		}
		else {
			$items .= '<li><a href="'. site_url('wp-login.php') .'">Log In</a></li>';
		}
		return $items;
	}
	
	function add_bhaa_profile_fields($user) 
	{
		$bhaa_runner_dateofrenewal = get_user_meta($user->ID,Runner::BHAA_RUNNER_DATEOFRENEWAL,true);
		$bhaa_runner_status = get_user_meta($user->ID,Runner::BHAA_RUNNER_STATUS,true);
		echo '<h3>BHAA Renewal</h3>';
		echo '<table class="form-table"><tbody>';
		
		echo '<tr>';
		echo '<th><label for="first_name">Status</label></th>';
		echo '<td><input type="text" name="status" id="status" value="'.$bhaa_runner_status.'" class="regular-text"></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<th><label for="first_name">Date of Renewal</label></th>';
		echo '<td><input type="text" name="status" id="status" value="'.$bhaa_runner_dateofrenewal.'" class="regular-text"></td>';
		echo '</tr>';
		
// 		echo '<tr>';
// 		echo '<th><label for="first_name">Payment</label></th>';
// 		echo '<td>'.get_permalink(get_page_by_title('membership')->ID).'</td>';
// 		echo '</tr>';
		
		echo '</tbody></table>';
	}

	/**
	 * Hack to get the booking button from the event manager
	 * 
	 * #_BOOKINGBUTTON see 'em_booking_button' filter
	 * Paid Membership Pro | Talking Manuals – Reviews
	 */
	function bhaa_annual_membership()
	{
		global $current_user;
		get_currentuserinfo();
		$status = get_user_meta(get_current_user_id(),'bhaa_runner_status',true);
		
		echo '<div>BHAA Membership Page</div>';
		echo('We will check your membership status to know what to do');
		echo('Welcome, ' . $current_user->display_name  . '</br>');
		echo('Your membership status is : <b>' . $status  . '</b></br>');
		
		$EM_Event = new EM_Event(array('post_id'=>205712));// 205712 LH - 205712
		//var_dump($EM_Event);
		
		// half works
		//echo $EM_Event->output_single();
		echo $EM_Event->output('<div id="annualmembershup">
				{has_bookings}
				#_BOOKINGFORM
				{/has_bookings}
				</div>');
	}
		
	function bhaa_manage_users_columns( $column ) {
		$column['status'] = __('Status', 'status');
		return $column;
	}
	
	function bhaa_manage_users_custom_column( $val, $column_name, $user_id ) 
	{
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case 'status' :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_STATUS,true); //$user->ID;
				break;
			default:
		}
		return $return;
	}
	
	/**
	 * http://net.tutsplus.com/tutorials/wordpress/quick-tip-making-a-fancy-wordpress-register-form-from-scratch/
	 */
	function bhaa_register_form()
	{
		echo '<p><label>First Name<br />';
		echo '<input id="bhaa_runner_firstname" class="input validate[required]" type="text" tabindex="20" size="20" value=""/></label></p>';
		
		echo '<p><label>Last Name<br />';
		echo '<input id="bhaa_runner_lastname" class="input validate[required]" type="text" tabindex="20" size="20" value=""/></label></p>';
		
		echo '<p><label for="user_login">Date Of Birth<br />';
		echo '<input type="text" class="input validate[custom[date]]" type="text" tabindex="20" size="20" name="bhaa_runner_dateofbirth" id="bhaa_runner_dateofbirth"/></label></p>';
		
		echo '<p><label for="bhaa_runner_gender">Gender<br />';
		echo '<input type="radio" name="gender" id="bhaa_runner_gender_male" class="validate[required]" value="Male">Male<br>';
		echo '<input type="radio" name="gender" id="bhaa_runner_gender_female" class="validate[required]" value="Female">Female<br></p>';
		
		echo '<p><label>Company<br/>';
		echo '<select name="bhaa_runner_company" id="bhaa_runner_company" class="validate[required]">';
		echo '<option value="Other">Other</option>';
		echo '</select>';
	}
	
	/**
	 * http://www.rarescosma.com/2011/04/wordpress-hooks-user_register/
	 */
	function bhaa_user_register($user_id, $password="", $meta=array()) 
	{
		// Gotta put all the info into an array
		$userdata = array();
		$userdata['ID'] = $user_id;
		
		// First name
		$userdata['first_name'] = $_POST['bhaa_runner_firstname'];
		$userdata['last_name'] = $_POST['bhaa_runner_lastname'];
		$userdata['last_name'] = $_POST['bhaa_runner_firstname'].'.'.$_POST['bhaa_runner_lastname'];
		
		// Enters into DB
		wp_update_user($userdata);
		
		// This is for custom meta data "gender" is the custom key and M is the value
		// update_usermeta($user_id, 'gender','M');
		update_user_meta( $user_id, 'bhaa_runner_firstname',$_POST['bhaa_runner_firstname']);
		update_user_meta( $user_id, 'bhaa_runner_lastname',$_POST['bhaa_runner_lastname']);
		update_user_meta( $user_id, 'bhaa_runner_dateofbirth',$_POST['bhaa_runner_dateofbirth']);
		update_user_meta( $user_id, 'bhaa_runner_gender',$_POST['bhaa_runner_gender']);
		update_user_meta( $user_id, 'bhaa_runner_company',$_POST['bhaa_runner_company']);
		update_user_meta( $user_id, BHAA_RUNNER_STATUS, DAY);
		
	}
}
?>