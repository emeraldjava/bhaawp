<html>
<head>
<link rel='stylesheet' id='twentytwelve-style-css' href='<?php echo get_stylesheet_uri();?>' type='text/css' media='all'/>
</head>
<body>
<div id="page">

<div id="header">
<header id="masthead" class="site-header" role="banner">
<hgroup>
<?php 
echo '<img class="bhaa-logo" src="'.get_stylesheet_directory_uri().'/images/logo.png" width="150" height="150" alt="BHAA Logo">';
?>
</hgroup>
</header>
</div>

<div id="main">
<?php 
foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking): 

$EM_Ticket_Booking->get_ticket(); 

?>
BHAA Ticket
<?php
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
//echo $EM_Ticket_Booking->get_ticket()->name; 
// http://wp-events-plugin.com/documentation/placeholders/
// http://wordpress.org/support/topic/plugin-events-manager-admin-bookings-booker-real-name-instead-of-email
// http://wordpress.org/support/topic/plugin-events-manager-booking-placeholders-not-working-in-mail
if($EM_Ticket_Booking->get_ticket()->name=='Annual Membership')
{
?>
Thanks #_BOOKINGNAME,

Your now a BHAA annual member. ID #_CONTACTID
<?php 
}
else 
{
?>
</hr>
Hi #_BOOKINGNAME,

Thank you for registering for the BHAA #_EVENTLINK event. 

Your Booking references is <b>#_BOOKINGID</b>

BHAA event 
When : #_EVENTDATES @ #_EVENTTIMES
Where : #_LOCATIONNAME - #_LOCATIONFULLLINE

Please note
- Turn up one hour before the race at #_24HSTARTTIME.
- Chip timing and returning race number.
- BHAA is a vol organisation.
- No HEADPHONES

Yours faithfully,
#_CONTACTNAME

Please email #_CONTACTEMAIL with any booking queries.
<?php 
}
?>
<?php endforeach; ?>
</div>
</div>
</body>
</html>