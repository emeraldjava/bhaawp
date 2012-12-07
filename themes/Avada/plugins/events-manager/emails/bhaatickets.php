<html>
<head>
<link rel='stylesheet' href='<?php echo site_url('/wp-content/themes/Avada/style.css');?>' type='text/css' media='all'/>
</head>
<body class="page">
<div id="page" class="hfeed site">

<header id="masthead" class="site-header" role="banner">
<hgroup>
<?php 
echo '<img class="bhaa-logo" src="'.get_stylesheet_directory_uri().'/images/logo.png" width="150" height="150" alt="BHAA Logo"/>';
?>
</hgroup>
</header>

<div id="main" class="wrapper">
<?php 
foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking): 
if($EM_Ticket_Booking->get_ticket()->name=='Annual Membership')
{
?>
<p>Hi #_BOOKINGNAME,</p>
<pre>
You are now a registered BHAA member for this year. Membership entitiles you to
- reduced entry at races
- run on a company team
- take part in the leagues
</pre>
<p>Your Booking references is <b>#_BOOKINGID</b>.</p>

<pre>
Yours faithfully,
#_CONTACTNAME
</pre>
<p>Please email #_CONTACTEMAIL with any booking queries.</p>
<?php 
}
else 
{
?>
</hr>
Hi #_BOOKINGNAME,

Thank you for registering for the BHAA #_EVENTLINK event. 

Your Booking references is <b>#_BOOKINGTICKETNAME #_BOOKINGID </b>

BHAA event 
When : #_EVENTDATES @ #_EVENTTIMES
Where : #_LOCATIONNAME - #_LOCATIONFULLLINE

Please note
- Turn up one hour before the race at #_24HSTARTTIME to collect your race number.
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
</div><!-- main -->
</div><!-- page -->
</body>
</html>