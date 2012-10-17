<?php
/**
 * Template Name: BHAA Register
 * A custom BHAA membership page
 * - will tell non registers to register
 * - for registered users it displays the annual ticket.
 * 
 * 
 * plugins\events-manager\classes\em-events.php
 * 
 */
?>

<?php get_header(); ?>

<?php 
if(!is_user_logged_in())
{
	echo '<div>Outline benefits here. Tell them to register and pay via the user profile.</div>';
	echo '<div>BHAA Membership Page - Please use the registration form to create a user account.</div>';
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
	
	$EM_Event = new EM_Event(array('post_id'=>2052));
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

<?php get_footer(); ?>