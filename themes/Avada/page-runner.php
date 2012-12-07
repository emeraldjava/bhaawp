<?php 
/**
 Template Name: BHAA Runner
*/
?>
<?php get_header();?>

<?php 
global $loader;
//echo 'BHAA Runner Page : Name = '.$_REQUEST['user_nicename'];
$user = get_user_by('slug', $_REQUEST['user_nicename']);
//var_dump($user);
// $user = get_user_by('nicename', $_REQUEST['user_nicename']);
// get_userd
// $user_nicename = $_REQUEST['name'];
// echo 'BHAA Runner Page : Name = '.$_REQUEST['name'];
// echo 'BHAA Runner Page : Name = '.$_GET['name'];
// //if (isset($wp_query->query_vars['name']))
// //{
// 	echo 'query var '.$wp_query->query_vars['name'];
// 	echo 'query var '.get_query_var( 'name' );
// //}

echo $loader->raceresult->getTable()->renderRunnerTable($user->ID);//$_REQUEST['id']);
?>
