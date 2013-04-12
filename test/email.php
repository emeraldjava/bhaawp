<?php
class Email
{
	function send($name)
	{
 		
		
// 		$line = "a,b,c,
// 		d,e,f,";
		
// 		$parsed = str_getcsv(
// 				$line, # Input line
// 				',',   # Delimiter
// 				'"',   # Enclosure
// 				'\n'   # Escape char
// 				);
// 		var_dump( $parsed );

// 		var_dump( time() );
 		$headers = 'MIME-Version: 1.0' . "\r\n";
 		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
 		$headers .= 'From: oconnellp@aegon.ie' . "\r\n";
 		
 		$email = "oconnellp@aegon.ie";
 		echo "hi ".$headers;
 		$res = mail($email,"test subject 3 ".$email,"test body 3 ".$email,$headers);
		echo $res;
		
// 		$args = array(
// 				'post_type'=> 'company',
// 				'name'    => $_REQUEST['term']);
// 		error_log('bhaawp_company_search '.$_REQUEST['term']);
// 		//		query_posts($args);
		
// 		$query = new WP_Query($args);//'pagename=contact' );
		
// 		error_log('bhaawp_company_search '.$query);
	}
}

$race = new Email();
$race->send("p");

?>