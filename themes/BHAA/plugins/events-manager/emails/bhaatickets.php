<html>
<body>
<img class="bhaa-logo" src="http://wpdemo.bhaa.ie/wp-content/themes/BHAA/images/logo.png" width="150" height="150" alt="BHAA Logo">
<div>
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
</div>
</body>
</html>