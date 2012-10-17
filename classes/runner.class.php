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
		add_filter('manage_users_columns',array($this,'bhaa_manage_users_columns'));
		add_filter('manage_users_custom_column',array($this,'bhaa_manage_users_custom_column'), 10, 3 );		

		add_action('show_user_profile',array(&$this,'add_bhaa_profile_fields'));
	}
	
	function add_bhaa_profile_fields($user) 
	{
		$bhaa_runner_dateofrenewal = get_user_meta($user->ID,Runner::BHAA_RUNNER_DATEOFRENEWAL,true);
		$bhaa_runner_status = get_user_meta($user->ID,Runner::BHAA_RUNNER_STATUS,true);
		echo '<h3>BHAA Renewal</h3>';
		echo '<table class="form-table"><tbody>';
		
		echo '<tr>';
		echo '<th><label for="first_name">Status</label></th>';
		echo '<td><input type="text" name="status" id="status" value="'.$bhaa_runner_dateofrenewal.'" class="regular-text"></td>';
		echo '</tr>';

		echo '<tr>';
		echo '<th><label for="first_name">Payment</label></th>';
		echo '<td>'.$this->display_realex_button().'</td>';
		echo '</tr>';
		
// 		echo '<div>Status '.$bhaa_runner_status.'</div>';
// 		echo '<div>Renewal Date '.$bhaa_runner_dateofrenewal.'</div>';
// 		if($bhaa_runner_status=="I")
// 		{
// 			echo '<div>Please renew your inactive membership.</div>';
// 			echo $this->display_realex_button();
// 		}
// 		elseif($bhaa_runner_status=="D")
// 		{
// 			echo '<div>Please become a BHAA member.</div>';
// 			echo $this->display_realex_button();
// 		}
// 		else
// 		{
// 			echo '<div>You are a current BHAA member.</div>';
// 		}
		echo '</tbody></table>';
	}
	
	/**
	 * 		<input type=hidden name="COMMENT1" value="Subscription for '.$days.' days.">
		<input type=hidden name="drt" value="'.$drt.'">
		<input type=hidden name="booking_id" value="1:'.$mem.':true">
	 * @return string
	 */
	function display_realex_button()
	{
		global $wpdb;
		$BHAA_Subscriptions = get_option( 'bhaa_subscription_default_values', "FALSE" );
		//get values from DB
		$merchantid = get_option('em_realex_redirect_merchant_id','A');
		$secret = get_option('em_realex_redirect_merchant_secret','B');
		$currency = get_option('dbem_bookings_currency','EUR');
		$mem = get_option('em_realex_redirect_mem');
		//$button_label = $BHAA_Subscriptions[button_label];
		//The code below is used to create the timestamp format required by Realex Payments
		$timestamp = strftime("%Y%m%d%H%M%S");
		$uid=get_current_user_id();
		$orderid = $uid."-".$timestamp;
		$price="15.00";
		$tmp = "$timestamp.$merchantid.$orderid.$price.$currency";
		$md5hash = md5($tmp);
		$tmp = "$md5hash.$secret";
		$md5hash = md5($tmp);
		$drt="$days:$role:$type";
		$ret='<div><form action="https://epage.payandshop.com/epage.cgi" method=post>
		<input type=hidden name="MERCHANT_ID" value="'.$merchantid.'">
		<input type=hidden name="ORDER_ID" value="'.$orderid.'">
		<input type=hidden name="CURRENCY" value="'.$currency.'">
		<input type=hidden name="AMOUNT" value="'.$price.'">
		<input type=hidden name="TIMESTAMP" value="'.$timestamp.'">
		<input type=hidden name="MD5HASH" value="'.$md5hash.'">
		<input type=hidden name="CUST_NUM" value="'.$uid.'">
		<input type=hidden name="REMOTE_ADDR" value="'.$_SERVER['REMOTE_ADDR'].'">
		<input type=hidden name="uid" value="'.$uid.'">
		<input type=hidden name="AUTO_SETTLE_FLAG" value="1">
		<input type=submit value="'.$button_label.'">
		</form>
		<a href="http://www.realexpayments.com"><img border="0" alt="online payments" title="online payments"
		src="http://www.realexpayments.com/images/logos/sm_gif.png" style="width:161px;heigth:111px;float:left;padding:0px;margin:0px;background:transparent;border:1px solid #D5D3D1;"/></a>';
		return $ret."</div>";
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
}
?>