<?php
/**
 * Template Name: BHAA Join
 * A custom BHAA membership page
 * - will tell non registers to register
 * - for registered users it displays the annual ticket.
 */
?>

<?php get_header(); ?>

<?php
// query for the membership page for its content
$your_query = new WP_Query('pagename=join');
while ( $your_query->have_posts() ) : $your_query->the_post();
echo the_content();
endwhile;
wp_reset_postdata();
?>


<?php 
/**
 * check the status of the runner - determine options
 */
/**
 * display the annual booking ticket and registration form.
 */
$bhaa_annual_event_id = get_option( 'bhaa_annual_event_id',0);
$event = em_get_event($bhaa_annual_event_id,'post_id');
if(!is_user_logged_in())
{
	//echo '<p>Your not LOGGED in.</p>';
	echo $event->output(
	'<div id="annualmembership">
	{has_bookings}
		#_BOOKINGFORM
	{/has_bookings}
	</div>');
}
else 
{
	global $current_user; 
	get_currentuserinfo();
	get_user_meta(get_current_user_id());
	
	$status = get_user_meta(get_current_user_id(),'bhaa_runner_status',true);
	$dateofrenewal = get_user_meta(get_current_user_id(),'bhaa_runner_dateofrenewal',true);
	
	if($status=="M" || $status=="I")
	{
		echo '<div>'.
			'<h3>Hi '.$current_user->display_name.'</h3>'.
			'<p>You are an existing BHAA member and renewals will be open from January 1st 2013. Thanks for your patience</p>'.
			'</div>';
	}
// 	else
// 	{
// 		echo $event->output(
// 			'<div id="annualmembership">
// 			{has_bookings}
// 			#_BOOKINGFORM
// 			{/has_bookings}
// 			</div>');
// 	}
}
?>
