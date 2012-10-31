<?php
// http://pythoughts.com/how-to-hide-that-you-use-wordpress/
remove_action('wp_head','wp_generator');

// em_new_user_notification





// http://www.wprecipes.com/customize-wordpress-login-logo-without-a-plugin
// function bhaa_login_logo() {
// 	echo '<style type="text/css">
// 	h1 a { 
// 	height:350px;
// 	margin-left:10px;
// 	margin-top:10px;
// 	width:410px;
// 	background-image:url('.content_url().'/themes/bhaatwentytwelve/images/logo.png) !important; }
// 	</style>';
// }
// add_action('login_head', 'bhaa_login_logo');

// http://www.paulund.co.uk/change-wordpress-login-logo-without-a-plugin
// function bhaa_login_logo_url() {
// 	return get_bloginfo( 'url' );
// }
// function bhaa_login_logo_url_title() {
// 	return 'BHAA';
// }

// add_filter('login_headertitle','bhaa_login_logo_url_title');
// add_filter('login_headerurl','bhaa_login_logo_url');

// // http://wpmu.org/how-to-simplify-wordpress-profiles-by-removing-personal-options/
// function hide_personal_options(){
// 	echo "\n" . '<script type="text/javascript">
// 	jQuery(document).ready(function($)
// 	{ 
// 		$(\'form#your-profile > h3:first\').hide(); 
// 		$(\'form#your-profile > table:first\').hide(); 
// 		$(\'form#your-profile\').show(); 
// 	});
// 	</script>' . "\n";
// }
// add_action('admin_head','hide_personal_options');

// // http://codex.wordpress.org/Plugin_API/Filter_Reference#Author_and_User_Filters
// // http://wpmu.org/remove-aim-yahoo-and-jabber-fields-from-the-wordpress-profile-page/
// function edit_contactmethods( $contactmethods ) {
// 	//$contactmethods['facebook'] = 'Facebook';
// 	//$contactmethods['twitter'] = 'Twitter';
// 	unset($contactmethods['yim']);
// 	unset($contactmethods['aim']);
// 	unset($contactmethods['jabber']);
// 	return $contactmethods;
// }
// add_filter('user_contactmethods','edit_contactmethods',10,1);


/**
 * http://wordpress.stackexchange.com/questions/19692/how-to-redirect-a-sucessful-registration-to-a-page-template
 * @return Ambigous <string, mixed>
 */
// function bhaa_registration_redirect()
// {
// 	return home_url( '/register' );
// }
// add_filter( 'registration_redirect', 'bhaa_registration_redirect' );

/**
 * favicon
 */
function favicon_link() {
	echo '<link rel="shortcut icon" type="image/x-icon" href="/wpdemo/favicon.ico" />' . "\n";
}
add_action('wp_head', 'favicon_link');


function wp_new_user_notification($user_id, $plaintext_pass = '') {
	$user = new WP_User($user_id);

	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

	@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

	if ( empty($plaintext_pass) )
		return;

	$message  = sprintf(__('Username: %s'), $user_login) . "\r\n";
	$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
	$message .= wp_login_url() . "\r\n";

	wp_mail($user_email, sprintf(__('[%s] Your username and password'), $blogname), $message);
}
?>