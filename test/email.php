<?php
class Email
{
	// DUBEXMAIL01.ds.global
	function send($name)
	{
 		$headers = 'MIME-Version: 1.0' . "\r\n";
 		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
 		$headers .= 'From: paul.oconnell@aegon.ie' . "\r\n";
 		
 		$email = "paul.oconnell@aegon.ie";
 		echo "hi ".$headers;
 		$res = mail($email,"test subject 3 ".$email,"test body 4 ".$email,$headers);
 		echo 'result: '.$res;
	}
}

$race = new Email();
$race->send("p");

?>