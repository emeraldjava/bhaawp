<?php
//get_header();

echo '<h1>POC RUNNER</h1>';

echo "<pre>GET "; print_r($_GET); echo "</pre>";
echo "<pre>POST "; print_r($_POST); echo "</pre>";
//echo var_dump($_REQUEST);

if(isset($_REQUEST['user_nicename']))
	$user = get_user_by('slug', $_REQUEST['user_nicename']);
else
	$user = get_user_by('id', 7713);// $_REQUEST['id']);

$metadata = get_user_meta($user->ID);
$status = $metadata['bhaa_runner_status'][0];
$company = $metadata['bhaa_runner_company'][0];

echo '<h1>'.$user->display_name.'</h1>';

// first section - general info
/* $content = apply_filters(
		'the_content',
		'[one_third last="no"]'.
		'<h2>BHAA Details</h2>'.
		'<ul>'.
		'<li><b>BHAA ID</b> : '.$user->ID.'</li>'.
		'<li>Standard : '.$metadata['bhaa_runner_standard'][0].'</li>'.
		(isset($company) ? '<li>Company : '.sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$company,get_post($company)->post_title).'</li>':'').
		'</ul>'.
		'[/one_third]');
echo $content; */

//get_footer();
?>