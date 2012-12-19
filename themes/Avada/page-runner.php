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

echo '<h2>'.$user->display_name.'</h2></br>';
echo '<h4>Results</h4>';
echo $loader->raceresult->getTable()->renderRunnerTable($user->ID);

// echo do_shortcode('[one_half last="no"]'.
// 	'[person name="'.$user->display_name.'"]plicabo. Nemo enim.[/person]'.
// 	'[/one_half]');

// echo do_shortcode('[content_box title="Results"]'
// 	.$loader->raceresult->getTable()->renderRunnerTable($user->ID).
// 	'[/content_box]');
?>
