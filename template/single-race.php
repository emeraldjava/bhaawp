<?php

/**
 * Template Name: BHAA Race Template
 * @package WordPress
 * @subpackage BHAA
 * @since Twenty Ten 1.0
 * 
 * http://sixrevisions.com/wordpress/wordpress-custom-post-types-guide/
 */

get_header(); ?>


<div id="container">

<div id="content" role="main">

<div id="bbp-user" class="bbp-single-user">

<div class="entry-content">

<!--  http://codex.wordpress.org/Function_Reference/the_ID -->

<p>WP Race ID : <?php echo get_the_ID();?></p>

<p>Meta information for this post:</p>
<?php the_meta(); ?>

<?php 
echo $loader->raceresult->getTable()->renderTable(get_post_meta(get_the_ID(),'bhaa_race_id',true));
?>
<hr/>

</div><!-- .entry-content -->

</div><!-- #bbp-user-->

</div><!-- #content -->

</div><!-- #container -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>