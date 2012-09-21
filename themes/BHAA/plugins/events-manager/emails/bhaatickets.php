<!-- 
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
-->

<?php foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking): ?>
BHAA Ticket
<?php
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
//echo $EM_Ticket_Booking->get_ticket()->name; 
if($EM_Ticket_Booking->get_ticket()->name=='Annual Membership')
{
?>
Thanks #_CONTACTNAME,

Your now a BHAA annual member. ID #_CONTACTID
<?php 
}
else 
{
?>
Hi #_CONTACTNAME,

BHAA event 
When : #_EVENTDATES @ #_EVENTTIMES

Where : #_LOCATIONNAME - #_LOCATIONFULLLINE

Yours faithfully,

#_CONTACTNAME
<?php 
}
?>

<?php endforeach; ?>

BHAA : Please email info@bhaa.ie with any booking queries.