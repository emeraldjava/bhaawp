<?php
/**
 * Template Name: BHAA Membership
 * A custom BHAA membership page
 * - will tell non registers to register
 * - for registered users it displays the annual ticket.
 * 
 * plugins\events-manager\classes\em-events.php
 * 
 */
?>

<?php get_header(); 
/**
 * The sales pitch - should be a static page
 */
?>
<div id="template-front-page" class="template-front-page">
<div>
<h1>BHAA Membership</h1>
<div>
<ol id="reasonstojoin">
<li>Reduced membership to all races</li>
<li>Run as a member of a team in prizes</li>
<li>Be counted in the summer and winter leagues</li>
<li>Easier registration on the day</li>
</ol>
</div>
</div>
<div id="right-sidebar" class="right-sidebar">
<?php 
/**
 * check the status of the runner - determine options
 */
if(!is_user_logged_in())
{
	echo '<p>Your not LOGGED in.</p>';
}
else 
{
	global $current_user; 
	get_currentuserinfo();
	
	$status = get_user_meta(get_current_user_id(),'bhaa_runner_status',true);
	$dateofrenewal = get_user_meta(get_current_user_id(),'bhaa_runner_dateofrenewal',true);
	
	echo '<div>BHAA Membership Page</div>';
	//echo('We will check your membership status to know what to do');
	echo('Welcome, ' . $current_user->display_name  . '</br>');
	echo('Your membership status is : <b>' . $status  . '</b>. Your last renewal date was '.$dateofrenewal.'</br>');
}

/**
 * display the annual booking ticket and registration form.
 */
$bhaa_annual_event_id = get_option( 'bhaa_annual_event_id',0);
$event = em_get_event($bhaa_annual_event_id,'post_id');
echo $event->output(
	'<div id="annualmembership">
	{has_bookings}
		#_BOOKINGFORM
	{/has_bookings}
	</div>');
?>
</div>
</div>
<?php get_footer(); ?>