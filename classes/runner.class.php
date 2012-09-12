<?php
/**
 * The runner class will handle user registration and editing of BHAA specific meta-data fields.
 * @author oconnellp
 *
 */
class Runner
{
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	function __construct()
	{
		add_action('show_user_profile',array(&$this,'add_bhaa_profile_fields'));
		add_action('edit_user_profile',array(&$this,'add_bhaa_profile_fields'));
		
		add_action('personal_options_update',array(&$this,'save_bhaa_user_profile_fields'));
		add_action('edit_user_profile_update',array(&$this,'save_bhaa_user_profile_fields'));
	}
	
	function Runner()
	{
		$this->__construct();
	}
	
	// http://bavotasan.com/2009/adding-extra-fields-to-the-wordpress-user-profile/
	function add_bhaa_profile_fields($user) {
	
		$bhaa_runner_address1 = get_usermeta($user->ID,Runner::BHAA_RUNNER_ADDRESS1);
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
		<input type="text" name="bhaa_runner_address2" id="bhaa_runner_address2" value="'.get_usermeta($user->ID,Runner::BHAA_RUNNER_ADDRESS2).'" class="regular-text" /><br />
		<span class="description">Please enter your address.</span>
		</td>
		</table>';
		
		echo '<table class="form-table">
		<tr>
		<th><label for="bhaa_runner_address2">Address 3</label></th>
		<td>
		<input type="text" name="bhaa_runner_address3" id="bhaa_runner_address3" value="'.get_usermeta($user->ID,Runner::BHAA_RUNNER_ADDRESS3).'" class="regular-text" /><br />
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