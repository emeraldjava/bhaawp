Total hack.

We'll add a new BHAATICKET token to the event manager logic which
allows us to control the email template via a custom php file.

class  : ./events-manager/classes/em-booking.php
method : function output($format, $target="html") line 564

case '#_BHAATICKETS':
	ob_start();
	em_locate_template('emails/bhaatickets.php', true, array('EM_Booking'=>$this));
	$replace = ob_get_clean();
	break;