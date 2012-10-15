<?php
require_once( ABSPATH . 'wp-content/plugins/events-manager/classes/em-event.php' );
// http://pythoughts.com/how-to-hide-that-you-use-wordpress/
remove_action('wp_head','wp_generator');

// Show less info to users on failed login for security. (Won't let a valid username be known)
function show_less_login_info()
{
	return "<strong>ERROR</strong>: What's wrong, don't remember? Try again...";
}
add_filter( 'login_errors', 'show_less_login_info' );

// // Don't generate and display WordPress version
// function no_generator()
// {
// 	return '';
// }

// add_filter( 'the_generator', 'no_generator' );


?>