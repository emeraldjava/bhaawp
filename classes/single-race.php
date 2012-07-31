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

Race
ID : <?php echo the_ID();?>

<p>Meta information for this post:</p>
<?php the_meta(); ?>

<p>Shortcode : <?php echo get_post_meta(get_the_ID(),'bhaa-race-id',true);?></p>
<?php echo do_shortcode('[bhaa type=raceresult id='.get_post_meta(get_the_ID(),'bhaa-race-id',true).']'); ?>

</div><!-- .entry-content -->

</div><!-- #bbp-user-<?php echo "HI"; ?> -->


</div><!-- #content -->

</div><!-- #container -->


<?php get_sidebar(); ?>

<?php get_footer(); ?>

