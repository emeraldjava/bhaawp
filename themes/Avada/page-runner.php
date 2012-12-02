<?php 
/**
 Template Name: BHAA Runner
*/
?>
<?php get_header(); ?>
BHAA Runner Page

<?php 
global $loader;
echo $loader->raceresult->getTable()->renderRunnerTable($_REQUEST['id']);
?>

<?php get_footer(); ?>