<?php 

echo "<pre>GET "; print_r($_GET); echo "</pre>";
echo "<pre>POST "; print_r($_POST); echo "</pre>";

//echo 'BHAA Runner Page : Name = '.$_REQUEST['user_nicename'];
if(isset($_REQUEST['user_nicename']))
	$user = get_user_by('slug', $_REQUEST['user_nicename']);
else if (isset($_REQUEST['id']))
	$user = get_user_by('id', $_REQUEST['id']);


if(isset($user->ID)){
	echo 'BHAA Runner Page '.print_r($user,true);	
}
else
	echo 'You have not selected a runner!.';
?>