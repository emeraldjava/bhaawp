<?php
/**
 * curl -v http://localhost/realex/
 * curl -v -X POST --data "a=b&c=d&MD5HASH=sadsd" http://localhost/realex
 *
 * @author oconnellp
 * 
 * http://bandhattonbutton.com/payment-result/
 * 
 */
class Realex {
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
	}
	
	function process() {
				
		if($_SERVER['REQUEST_METHOD']=='POST') {
			return $this->handle_post();
		} else {
			$out = '<h1>Custom Realex Class - '.$_SERVER['REQUEST_METHOD'].'</h1>';
			//$out .= '<h2>';
			//$out .= print_r($_REQUEST,true);
			//$out .= '</h2>';
			return $out;			
		}
	}
	
	private function handle_post() {
		//$out = '<h1>Custom Realex Class - '.$_SERVER['REQUEST_METHOD'].'</h1>';
		//$out .= '<h2>';
		//$out .= print_r($_REQUEST,true);
		//$out .= '</h2>';
		//$out .= '<h2>MD5HASH : '.$_POST['MD5HASH'].'</h2>';
		
		$timestamp = $_POST['TIMESTAMP'];
		$orderid = $_POST['ORDER_ID'];
		$result = $_POST['RESULT'];
		$message = $_POST['MESSAGE'];
		$pasref = $_POST['PASREF'];
		$authcode = $_POST['AUTHCODE'];
		$realexmd5 = $_POST['MD5HASH'];
		$user_id = $_POST['uid'];
		$merchantid = get_option( 'em_realex_redirect_merchant_id', "FALSE" );
		$secret = get_option( 'em_realex_redirect_merchant_secret', "FALSE" );
		
		//---------------------------------------------------------------
		$tmp = "$timestamp.$merchantid.$orderid.$result.$message.$pasref.$authcode";
		$md5hash = md5($tmp);
		$tmp = "$md5hash.$secret";
		$md5hash = md5($tmp);
		
		//Check to see if hashes match or not
		$ip=$_POST['REMOTE_ADDR'];
		$out='<!DOCTYPE html>
			<html>
				<head>
					<title>BHAA Realex IPN</title>
					<meta charset="utf-8">
				</head>
				<body>';
		$msg="";
		if (!file_exists('./logs')) {
			mkdir('./logs');
			//$msg='$status,$user_id,$orderid,$timestamp,$ip'.PHP_EOL;
		}
		//if(!file_exists('../logs/ipn.csv')){
		//	$msg='$status,$user_id,$orderid,$timestamp,$ip'.PHP_EOL;
		//}
		
		if ($timestamp) {
			$timestamp = date('Y-m-d', strtotime($timestamp));
		} else {
			$timestamp = date('Y-m-d');
		}
		if ($md5hash != $realexmd5) {
			$out.="<p>hashes don't match - response not authenticated!<br />This is not an authenticated Realex Request.</p>";
			error_log("realex-ipn : hashes don't match - response not authenticated!");
			file_put_contents('./logs/ipn.csv', $msg."-100000,$user_id,$orderid,$timestamp,$ip".PHP_EOL, FILE_APPEND);
		}
		else
		{
			// md5 is correct. authorised response from RealEx Servers
			error_log('realex-ipn:'."$result,$user_id,$orderid,$timestamp,$ip");
			file_put_contents('./logs/ipn.csv', $msg."$result,$user_id,$orderid,$timestamp,$ip".PHP_EOL, FILE_APPEND);
			//switch the result
			$new_status = false;
			//Common variables
			$amount = ($_POST['AMOUNT']/100);
			$currency = $_POST['CURRENCY'];
		
			error_log('realex-ipn:$booking_string='.$_POST['booking_id']);
			$custom_values = explode(':', $_POST['booking_id']);
			error_log('realex-ipn:$booking_id='.$custom_values[0]);
			$booking_id = !empty($custom_values[0]) ? $custom_values[0]:0;
			$event_id = !empty($custom_values[1]) ? $custom_values[1]:0;
			$SUB = strtolower($custom_values[2])=='true'?true:false;
			error_log('realex-ipn:$booking_id='.$booking_id);
			error_log('realex-ipn:$event_id='.$event_id);
			error_log('realex-ipn:$sub='.$SUB);
			$EM_Booking = new EM_Booking($booking_id);
			if ( !empty($EM_Booking->booking_id) && count($custom_values) == 3 )
			{
				//booking exists
				$EM_Booking->manage_override = true; //since we're overriding the booking ourselves.
				$user_id = $EM_Booking->person_id;
				error_log('realex-ipn:$user_id='.$user_id);
				// process realex_redirect response
		
				$membertype = get_user_meta($user_id,"bhaa_runner_status",true);
				if(trim($membertype)==""){
					$membertype="D";
				}
				error_log('realex-ipn:$membertype='.$membertype);
		
				switch ($result) {
					case '00':// case: successful payment
						$note="Successful Payment";
						$this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['order_id'], $result, $note);
						$user_data = array();
						if ( !empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration']) ) {
							foreach ($EM_Booking->booking_meta['registration'] as $fieldid => $field) {
								if ( trim($field) !== '' ) {
									$user_data[$fieldid] = $field;
								}
							}
						}
						if ( $amount >= $EM_Booking->get_price(false, false, true) && (!get_option('em_realex_redirect_manual_approval', false) || !get_option('dbem_bookings_approval')) ) {
							$EM_Booking->approve(true, true); //approve and ignore spaces
						} else {
							$EM_Booking->set_status(0); //Set back to normal "pending"
						}
							
						//! START CUSTOM TICKET HANDLING AREA
						switch($membertype){
							case "D":
							case "M":
							case "I":
							default:
								foreach($EM_Booking->tickets as $ticket){
									switch(strtolower($ticket->ticket_name))
									{
										case "annual membership"://process membership
											$status_res = update_user_meta( $user_id, "bhaa_runner_status", "M");
											error_log('realex-ipn:AM bhaa_runner_status='.$user_id.':'.$status_res);
											$date_res = update_user_meta( $user_id, "bhaa_runner_dateofrenewal", $timestamp);
											error_log('realex-ipn:AM bhaa_runner_dateofrenewal='.$timestamp.':'.$date_res);
											break;
										case "day member ticket":
											$status_res = update_user_meta( $user_id, "bhaa_runner_status", "D");
											error_log('realex-ipn:DAY MEMBER bhaa_runner_status='.$user_id.':'.$status_res);
											// "bhaa member ticket"
										default:
											//process ticket specific actions here
											error_log('realex-ipn:ticketname='.$ticket->ticket_name);
											break;
									}
								}
								break;
						}
						do_action('em_payment_processed', $EM_Booking, $this);
						//! END CUSTOM TICKET HANDLING AREA
						$out.='<p>Thank you, your payment has been successful.<br /><br />To continue browsing please return to <a href="'.site_url().'"><b>'.site_url().'</b></a><br /><br /></p>';
						break;
					case '101':
					case '102':// case: denied
						$note = 'Last transaction has been reversed. Reason: Payment Denied';
						$this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['order_id'], $result, $note);
						$EM_Booking->cancel();
						do_action('em_payment_denied', $EM_Booking, $this);
						$out .= '<p>There was an error processing your payment.<br />To try again please go back to the <a href="'.site_url().'"><b>'.site_url().'</b></a> event page</p>';
						break;
					default:
						$out .= '<p>There was an error processing your payment.<br />To try again please go back to the <a href="'.site_url().'"><b>'.site_url().'</b></a> event page</p>';
				}
				$out.='<p><small>You have been sent an email to the account: <b>'.$EM_Booking->get_person()->user_email.'</b> to confirm these details.</small></p>';
			}
			else
			{
				// ! has been accepted before
				if ( $result == "00" )
				{
					$message = apply_filters('em_gateway_realex_redirect_bad_booking_email', "
			A Payment has been received by realex for a non-existent booking.
		
			Event Details : %event%
		
			It may be that this user's booking has timed out yet they proceeded with payment at a later stage.
		
			To refund this transaction, you must go to your realex account and search for this transaction:
		
			Transaction ID : %transaction_id%
			Email : %payer_email%
		
			When viewing the transaction details, you should see an option to issue a refund.
		
			If there is still space available, the user must book again.
		
			Sincerely,
			BHAA Events Manager", $booking_id, $event_id);
		
					if ( !empty($event_id) )
					{
						$EM_Event = new EM_Event($event_id);
						$event_details = $EM_Event->name . " - " . date_i18n(get_option('date_format'), $EM_Event->start);
					}
					else
					{
						$event_details = __('Unknown', 'em-pro');
					}
					$message  = str_replace(array('%transaction_id%', '%payer_email%', '%event%'), array($_POST['order_id'], $_POST['payer_email'], $event_details), $message);
					wp_mail(get_option('em_realex_redirect_email' ), __('Unprocessed payment needs refund'), $message);
					$out .='Error: This payment has been received, but we do not know what booking it is for. Management has been notified. The booking number is :'.$EM_Booking->booking_id;
				}
				else
				{
					$out .='Error: Bad IPN request, custom ID does not correspond with any pending booking. Payment was not taken.';
				}
			}
		}
		$out.="</body></html>";
		//echo $out;
		
		return $out;
	}
	
	function record_transaction($EM_Booking, $amount, $currency, $timestamp, $txn_id, $status, $note) {
		global $wpdb;
		$data = array();
		$data['booking_id'] = $EM_Booking->booking_id;
		$data['transaction_gateway_id'] = $txn_id;
		$data['transaction_timestamp'] = $timestamp;
		$data['transaction_currency'] = $currency;
		$data['transaction_status'] = $status;
		$data['transaction_total_amount'] = $amount;
		$data['transaction_note'] = $note;
		$data['transaction_gateway'] = "realex_redirect";
	
		if ( !empty($txn_id) ) {
			$existing = $wpdb->get_row( $wpdb->prepare( "SELECT transaction_id, transaction_status, transaction_gateway_id, transaction_total_amount FROM ".EM_TRANSACTIONS_TABLE." WHERE transaction_gateway = %s AND transaction_gateway_id = %s", "realex_redirect", $txn_id ) );
		}
		$table = EM_TRANSACTIONS_TABLE;
		if ( is_multisite() && !EM_MS_GLOBAL && !empty($EM_Event->blog_id) && !is_main_site($EM_Event->blog_id) ) {
			//we must get the prefix of the transaction table for this event's blog if it is not the root blog
			$table = $wpdb->get_blog_prefix($EM_Event->blog_id).'em_transactions';
		}
		if ( !empty($existing->transaction_gateway_id) && $amount == $existing->transaction_total_amount && $status != $existing->transaction_status ) {
			// Update only if txn id and amounts are the same (e.g. pending payments changing status)
			$wpdb->update( $table, $data, array('transaction_id' => $existing->transaction_id) );
		} else {
			// Insert
			$wpdb->insert( $table, $data );
		}
	}
}
?>