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
 
 CIMY
 http://wordpress.org/extend/plugins/cimy-user-extra-fields/developers/
 http://blog.ftwr.co.uk/archives/2009/07/19/adding-extra-user-meta-fields/
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
	//const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	const BHAA_RUNNER_TERMS_AND_CONDITIONS = 'bhaa_runner_terms_and_conditions';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	const BHAA_RUNNER_GENDER = 'bhaa_runner_gender';
	const MAN = 'M';
	const WOMAN = 'W';
	
	function Runner()
	{
		// user profile fields CRUD
		add_action('show_user_profile',array(&$this,'bhaa_profile_fields'));
		add_action('edit_user_profile',array(&$this,'bhaa_profile_fields'));
 		add_action('personal_options_update',array(&$this,'bhaa_save_user_profile_fields'));
 		add_action('edit_user_profile_update',array(&$this,'bhaa_save_user_profile_fields'));
		
		// registration fields CRUD
		add_action('register_form',array($this,'bhaa_register_form'));
		add_action('user_register',array($this,'bhaa_user_register'));
		
		// customise login filter
		remove_filter('authenticate',array(&$this,'wp_authenticate_username_password'), 20, 3);
		add_filter('authenticate',array(&$this,'bhaa_authenticate_username_password'), 20, 3);
		
		// display the admin status column
		add_filter('manage_users_columns',array($this,'bhaa_manage_users_columns'));
 		add_filter('manage_users_custom_column',array($this,'bhaa_manage_users_custom_column'), 10, 3 );
	}
	
	/**
	 * enable username or email login
	 */
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
	
	function bhaa_profile_fields($user) 
	{
		echo '<h3>BHAA Fields</h3>';
		echo '<table class="form-table"><tbody>';
		
		$bhaa_runner_dateofbirth = get_user_meta($user->ID,Runner::BHAA_RUNNER_DATEOFBIRTH,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_DATEOFBIRTH.'">Date Of Birth</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_DATEOFBIRTH.'" id="'.Runner::BHAA_RUNNER_DATEOFBIRTH.'" value="'.$bhaa_runner_status.'" class="regular-text"></td>';
		echo '</tr>';
		
		$bhaa_runner_mobilephone = get_user_meta($user->ID,Runner::BHAA_RUNNER_MOBILEPHONE,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_MOBILEPHONE.'">Mobile Phone</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_MOBILEPHONE.'" id="'.Runner::BHAA_RUNNER_MOBILEPHONE.'" value="'.$bhaa_runner_mobilephone.'" class="regular-text"></td>';
		echo '</tr>';

		$bhaa_runner_textalert = get_user_meta($user->ID,Runner::BHAA_RUNNER_TEXTALERT,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_TEXTALERT.'">Text Alert</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_TEXTALERT.'" id="'.Runner::BHAA_RUNNER_TEXTALERT.'" value="'.$bhaa_runner_textalert.'" class="regular-text"></td>';
		echo '</tr>';
		
		$bhaa_runner_status = get_user_meta($user->ID,Runner::BHAA_RUNNER_STATUS,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_STATUS.'">Membership Status</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_STATUS.'" id="'.Runner::BHAA_RUNNER_STATUS.'" value="'.$bhaa_runner_status.'" class="regular-text"></td>';
		echo '</tr>';

		$bhaa_runner_dateofrenewal = get_user_meta($user->ID,Runner::BHAA_RUNNER_DATEOFRENEWAL,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_DATEOFRENEWAL.'">Date of Renewal</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_DATEOFRENEWAL.'" id="'.Runner::BHAA_RUNNER_DATEOFRENEWAL.'" value="'.$bhaa_runner_dateofrenewal.'" class="regular-text"></td>';
		echo '</tr>';
		
		$bhaa_runner_address1 = get_user_meta($user->ID,Runner::BHAA_RUNNER_ADDRESS1,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_ADDRESS1.'">Address 1</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_ADDRESS1.'" id="'.Runner::BHAA_RUNNER_ADDRESS1.'" value="'.$bhaa_runner_address1.'" class="regular-text"></td>';
		echo '</tr>';
		
		$bhaa_runner_address2 = get_user_meta($user->ID,Runner::BHAA_RUNNER_ADDRESS2,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_ADDRESS2.'">Address 2</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_ADDRESS2.'" id="'.Runner::BHAA_RUNNER_ADDRESS2.'" value="'.$bhaa_runner_address2.'" class="regular-text"></td>';
		echo '</tr>';
		
		$bhaa_runner_address3 = get_user_meta($user->ID,Runner::BHAA_RUNNER_ADDRESS3,true);
		echo '<tr>';
		echo '<th><label for="'.Runner::BHAA_RUNNER_ADDRESS3.'">Address 3</label></th>';
		echo '<td><input type="text" name="'.Runner::BHAA_RUNNER_ADDRESS3.'" id="'.Runner::BHAA_RUNNER_ADDRESS3.'" value="'.$bhaa_runner_address3.'" class="regular-text"></td>';
		echo '</tr>';
						
		echo '</tbody></table>';
	}
	
	function bhaa_save_user_profile_fields( $user_id ) 
	{
		if ( !current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		update_user_meta( $user_id, Runner::BHAA_RUNNER_ADDRESS1, $_POST[Runner::BHAA_RUNNER_ADDRESS1] );
		update_user_meta( $user_id, Runner::BHAA_RUNNER_ADDRESS2, $_POST[Runner::BHAA_RUNNER_ADDRESS2] );
		update_user_meta( $user_id, Runner::BHAA_RUNNER_ADDRESS3, $_POST[Runner::BHAA_RUNNER_ADDRESS3] );
	}
	
	/**
	 * handle the custom admin columns
	 */
	function bhaa_manage_users_columns( $column ) {
		$column['status'] = __('Status', 'status');
		return $column;
	}
	
	function bhaa_manage_users_custom_column( $val, $column_name, $user_id ) 
	{
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case 'status' :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_STATUS,true);
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
		//echo '<p><label>First Name<br />';
		//echo '<input name="bhaa_runner_firstname" class="input validate[required]" type="text" tabindex="20" size="20" value=""/></label></p>';
		
		//echo '<p><label>Last Name<br/>';
		//echo '<input name="bhaa_runner_lastname" class="input validate[required]" type="text" tabindex="20" size="20" value=""/></label></p>';
		
		echo '<p><label for="bhaa_runner_dateofbirth">Date Of Birth<br/>';
		echo '<input name="bhaa_runner_dateofbirth" id="bhaa_runner_dateofbirth" type="text" class="input validate[custom[date]]" type="text" tabindex="20" size="20"/></label></p>';
		
		echo '<p><label for="bhaa_runner_gender">Gender<br/>';
		echo '<input type="radio" name="bhaa_runner_gender" id="bhaa_runner_gender_male" class="validate[required]" value="Male">Male<br>';
		echo '<input type="radio" name="bhaa_runner_gender" id="bhaa_runner_gender_female" class="validate[required]" value="Female">Female<br></p>';
		
		echo '<p><label>Company<br/>';
		echo $this->bhaa_houses_dropdown( 'house' );
		echo '</p>';
		
		echo '<p><label for="'.Runner::BHAA_RUNNER_TERMS_AND_CONDITIONS.'">Accept BHAA Terms and Conditions<br/>';
		echo '<input name="'.Runner::BHAA_RUNNER_TERMS_AND_CONDITIONS.'" id="'.Runner::BHAA_RUNNER_TERMS_AND_CONDITIONS.'" type="checkbox" class="input validate[custom[checkbox]]"/></label></p>';
	}
	
	/**
	 * http://codex.wordpress.org/Function_Reference/get_posts
	 * http://codex.wordpress.org/Function_Reference/wp_dropdown_categories
	 * http://wordpress.stackexchange.com/questions/34320/dropdown-list-of-a-custom-post-type
	 * http://stackoverflow.com/questions/698817/faster-way-to-populate-select-with-javascript
	 */
	function bhaa_houses_dropdown( $post_type )
	{
		error_log('bhaa_houses_dropdown');
		$posts = get_posts(
				array(
						'post_type'   => $post_type,
						'numberposts' => -1,
						'orderby'     => 'title',
						'order'       => 'ASC'
				)
		);
		if( ! $posts ) return;
	
		$out = '<select name="bhaa_runner_company"><option>Select a Company</option>';
		foreach( $posts as $p )
		{
			$out .= '<option value="' . get_permalink( $p->ID ) . '">' . esc_html( $p->post_title ) . '</option>';
		}
		$out .= '</select>';
		return $out;
	}
	
	/**
	 * http://www.rarescosma.com/2011/04/wordpress-hooks-user_register/
	 */
	function bhaa_user_register($user_id, $password="", $meta=array()) 
	{
		error_log("bhaa_user_register ".$user_id.' '.$meta);
		// Gotta put all the info into an array
		$userdata = array();
		$userdata['ID'] = $user_id;
		
		// http://codex.wordpress.org/Function_Reference/get_userdata
		$userdata['display_name'] = $_POST['bhaa_runner_firstname'].'.'.$_POST['bhaa_runner_lastname'];
		$userdata['user_nicename'] = $_POST['bhaa_runner_firstname'].'.'.$_POST['bhaa_runner_lastname'];
		wp_update_user($userdata);

		update_user_meta( $user_id, 'first_name',$_POST['bhaa_runner_firstname']);
		update_user_meta( $user_id, 'last_name',$_POST['bhaa_runner_lastname']);
		update_user_meta( $user_id, 'nickname', $_POST['bhaa_runner_firstname'].'.'.$_POST['bhaa_runner_lastname']);
		//update_user_meta( $user_id, 'bhaa_runner_firstname',$_POST['bhaa_runner_firstname']);
		//update_user_meta( $user_id, 'bhaa_runner_lastname',$_POST['bhaa_runner_lastname']);
		update_user_meta( $user_id, 'bhaa_runner_dateofbirth',$_POST['bhaa_runner_dateofbirth']);
		update_user_meta( $user_id, 'bhaa_runner_gender',$_POST['bhaa_runner_gender']);
		update_user_meta( $user_id, 'bhaa_runner_company',$_POST['bhaa_runner_company']);
		update_user_meta( $user_id, Runner::BHAA_RUNNER_STATUS, Runner::DAY);
	}
}
?>