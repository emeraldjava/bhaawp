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

<?php get_header(); ?>

<div id="template-front-page" class="template-front-page">
<div>
<h1>BHAA Membership</h1>
<p>This is the sales pitch to people.</p>
<p>Currently implemented as a Page Template.</p>
<ul>
<li>Reduced membership to all races</li>
<li>Run as a member of a team</li>
<li>Races count towards the leagues</li>
</ul>
</div>
<div id="right-sidebar" class="right-sidebar">
<?php 
if(!is_user_logged_in())
{
	echo '<p>Outline benefits here. Tell them to register and pay via the user profile.</p>';
	echo '<p>BHAA Membership Page - Please use the <a href="./register">registration form</a> to create a user account.</p>';
}
else 
{
	global $current_user; 
	get_currentuserinfo();
	
	$status = get_user_meta(get_current_user_id(),'bhaa_runner_status',true);
	
	echo '<div>BHAA Membership Page</div>';
	echo('We will check your membership status to know what to do');
	echo('Welcome, ' . $current_user->display_name  . '</br>');
	echo('Your membership status is : <b>' . $status  . '</b></br>');
	
	$EM_Event = new EM_Event(array('post_id'=>205712));// 205712 - 2052
	//var_dump($EM_Event);
	
	// half works
	//echo $EM_Event->output_single();
	echo $EM_Event->output('<div id="annualmembershup">
	{has_bookings}
			#_BOOKINGFORM
			{/has_bookings}
			</div>');
			
	if(isset($status) && $status==("I"))
	{
		//echo('Please renew - via this shortcode hack.</br>');
		//echo do_shortcode('[bhaaraceday]');
	}
	else
	{
		//echo('You are a current member of the BHAA.');
	}
}
?>
</div>
</div>
<?php get_footer(); ?>