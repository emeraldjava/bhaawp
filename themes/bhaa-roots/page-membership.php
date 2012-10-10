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
	echo '<div>BHAA Membership Page - We will check your membership status to know what to do</div>';
}
?>

<?php get_footer(); ?>