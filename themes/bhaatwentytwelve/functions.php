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

/**
 * http://codex.wordpress.org/Function_Reference/wp_dropdown_categories
 * http://wordpress.stackexchange.com/questions/34320/dropdown-list-of-a-custom-post-type
 * @param unknown_type $post_type
 * @return void|string
 */
function bhaa_houses_dropdown( $post_type )
{
	error_log('bhaa_houses_dropdown');
	$posts = get_posts(
			array(
					'post_type'  => $post_type,
					'numberposts' => -1
			)
	);
	if( ! $posts ) return;

	$out = '<select id="bhaa_runner_company"><option>Select a Company</option>';
	foreach( $posts as $p )
	{
		$out .= '<option value="' . get_permalink( $p ) . '">' . esc_html( $p->post_title ) . '</option>';
	}
	$out .= '</select>';
	return $out;
}

/**
 * 
 * http://stackoverflow.com/questions/698817/faster-way-to-populate-select-with-javascript
 */
function bhaa_house_drop_down_list() {
	error_log('bhaa_house_drop_down_list');
	print '
		<script>function addOption(selectbox,text,value,selected)
		{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
		if(selected=="1")
			optn.selected="selected";
		selectbox.options.add(optn);
		};</script>
		';
	global $wpdb;//$current_user->user_id
	global $current_user;
	$c = get_user_meta (get_current_user_id(), 'company', true);
	//get's the current users row with company info
	$query = "SELECT post_title FROM ".$wpdb->prefix ."posts WHERE post_status = 'publish' AND post_type = 'house' order by post_title ASC";
	$items = $wpdb->get_results($query);//get items as assoc array.
	print '<script>
 	if(document.getElementsByName("bhaa_runner_company")[0]){
	';
	foreach ($items as $row) {//give individual items
		print 'addOption( document.getElementsByName("bhaa_runner_company")[0],"'.$row->post_title.'","'.$row->post_title.'"';
		if ($row->post_title==$c) {
			print',"1");';
		}else {
			print',"0");';
		}
	}
	print '}</script>';
}
add_action('wp_footer', 'bhaa_house_drop_down_list');
add_action('admin_footer', 'bhaa_house_drop_down_list');
?>