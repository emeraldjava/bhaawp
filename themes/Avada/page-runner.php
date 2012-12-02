<?php 
/**
 Template Name: BHAA Runner Results

 http://www.expand2web.com/blog/custom-page-template-wordpress
*/
?>
<?php get_header(); ?>
BHAA Runner Page

<?php 
global $loader;
echo $loader->raceresult->getTable()->renderRunnerTable($_REQUEST['id']);
?>

<?php get_footer(); ?>