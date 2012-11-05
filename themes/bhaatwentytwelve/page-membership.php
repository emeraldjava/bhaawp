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
if(!is_user_logged_in())
{
	echo '<p>We first need you to register your details onlines.</p>';
	echo '<p>BHAA Membership Page - Please use the <a href="./register">registration form</a> to create a user account.</p>';
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
	
	/**
	 * 2052 - wpdemo
	 * 205712 - ae local
	 */
	$EM_Event = new EM_Event(array('post_id'=>205712));// 205712 - 2052
			
	if(isset($status) && $status==("I"))
	{
		//echo('Please renew - via this shortcode hack.</br>');
		// half works
		//echo $EM_Event->output_single();
		echo $EM_Event->output(
		'<div id="annualmembershup">
		{has_bookings}
			#_BOOKINGFORM
		{/has_bookings}
		</div>');
	}
	else
	{
		echo('Membership renewal can be done again next year.');
	}
}
?>
</div>
</div>
<?php get_sidebar('membership'); ?>
<?php get_footer(); ?>