<html>
<body>
<?php 
echo '<img class="bhaa-logo" src="'.get_stylesheet_directory_uri().'/images/logo.png" width="150" height="150" alt="BHAA Logo">';
?>
<div>
<?php 
foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking): ?>
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

--------
#_CONTACTNAME
Name of the contact person for this event (as shown in the dropdown when adding an event).
#_CONTACTUSERNAME
Contact person's username.
#_CONTACTEMAIL
E-mail of the contact person for this event.
#_CONTACTPHONE
Phone number of the contact person for this event. Can be set in the user profile page.
#_CONTACTAVATAR
Contact person's avatar.
#_CONTACTPROFILELINK
Contact person's "Profile" link. Only works with BuddyPress enabled.
#_CONTACTPROFILEURL
Contact person's profile url. Only works with BuddyPress enabled.
#_CONTACTID
Contact person's WordPress user ID.
#_CONTACTMETA

-----------

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