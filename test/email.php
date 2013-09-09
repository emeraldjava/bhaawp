<?php
class Email
{
	function send($name)
	{
 		$headers = 'MIME-Version: 1.0' . "\r\n";
 		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
 		$headers .= 'From: oconnellp@aegon.ie' . "\r\n";
 		
 		$email = "oconnellp@aegon.ie";
 		echo "hi ".$headers;
 		$res = mail($email,"test subject 3 ".$email,"test body 3 ".$email,$headers);
 		echo 'result: '.$res;
	}
}

$race = new Email();
$race->send("p");

?>