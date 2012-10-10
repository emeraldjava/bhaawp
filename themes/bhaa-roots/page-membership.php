<?php
/**
 * A custom BHAA membership page
 * - will tell non registers to register
 * - for registered users it displays the annual ticket.
 */
?>

<?php get_header(); ?>

<?php 
if(!is_user_logged_in())
{
	echo '<div>BHAA Membership Page - Please use the registration form to create a userr account.</div>';
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
	if(isset($status) && $status==("I"))
	{
		echo('Please renew.');
	}
	else
	{
		echo('You are a current member of the BHAA.');
	}
}
?>

<?php get_footer(); ?>