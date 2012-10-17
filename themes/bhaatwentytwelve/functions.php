<?php
// http://pythoughts.com/how-to-hide-that-you-use-wordpress/
remove_action('wp_head','wp_generator');

// http://www.wprecipes.com/customize-wordpress-login-logo-without-a-plugin
function bhaa_login_logo() {
	echo '<style type="text/css">
	h1 a { 
	height:350px;
	margin-left:10px;
	margin-top:10px;
	width:145px;
	background-image:url('.content_url().'/themes/bhaatwentytwelve/images/logo.png) !important; }
	</style>';
}
add_action('login_head', 'bhaa_login_logo');
?>