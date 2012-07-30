<?php


/**

* Single Topic

*

* @package bbPress

* @subpackage Theme

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


</div><!-- .entry-content -->

</div><!-- #bbp-user-<?php echo "HI"; ?> -->


</div><!-- #content -->

</div><!-- #container -->


<?php get_sidebar(); ?>

<?php get_footer(); ?>

