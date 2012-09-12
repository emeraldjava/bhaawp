<?php
/**
 * The runner class will handle user registration and editing of BHAA specific meta-data fields.
 * @author oconnellp
 *
 */
class Runner
{
	function __construct()
	{
		add_action('show_user_profile',array(&$this, 'add_extra_profile_fields'));
		add_action('edit_user_profile',array(&$this, 'extra_user_profile_fields'));
	}
	
	function Runner()
	{
		$this->__construct();
	}
	
	// http://bavotasan.com/2009/adding-extra-fields-to-the-wordpress-user-profile/
	function add_extra_profile_fields($user) {
	
		$bhaa_runner_address1 = get_usermeta($user->ID,'bhaa_runner_address1');
		echo '<h3>BHAA Data</h3>';
		echo '<table class="form-table">
		<tr>
		<th><label for="address">Address 1</label></th>
		<td>
		<input type="text" name="address" id="address" value="'.$bhaa_runner_address1.'" class="regular-text" /><br />
		<span class="description">Please enter your address.</span>
		</td>
		</table>';
		
		echo '<table class="form-table">
		<tr>
		<th><label for="address">Address 2</label></th>
		<td>
		<input type="text" name="address" id="address" value="'.get_usermeta($user->ID,'bhaa_runner_address2').'" class="regular-text" /><br />
		<span class="description">Please enter your address.</span>
		</td>
		</table>';
		// add extra profile fields to user edit page
	}
}
?>