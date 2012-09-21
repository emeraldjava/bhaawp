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
<?php
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
echo $EM_Ticket_Booking->get_ticket()->name; 
?>

Quantity: <?php echo $EM_Ticket_Booking->get_spaces(); ?>

Price: <?php echo em_get_currency_symbol(!get_option('dbem_smtp_html'))." ". number_format($EM_Ticket_Booking->get_price(),2); ?>

<?php endforeach; ?>

BHAA : Please email info@bhaa.ie with any booking queries.