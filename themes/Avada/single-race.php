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

<?php 
// TODO - LINK THE EVENT URL HERE

//echo 'p2p_from '.$_REQUEST('p2p_from').' p2p_to '.$p2p_to;

//$related = p2p_type( 'event_to_race' )->set_direction( 'to' )->get_connected( get_the_ID() );
//echo $related->the_post()->ID;
//$related = p2p_type( 'event_to_race' )->get_related( get_queried_object() );
//echo $related->the_post()->ID;
//var_dump($related);
?>

<?php
// Find connected posts
$connected = new WP_Query( array(
  'connected_type' => 'event_to_race',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
) );

// Display connected posts
if ( $connected->have_posts() ) :
?>
<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
	<h3>Event : <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<?php endwhile; ?>

<?php 
// Prevent weirdness
wp_reset_postdata();

endif;
?>


<p>Meta information for this post:</p>
<?php the_meta(); ?>

<?php 
echo $loader->raceresult->getTable()->renderTable(get_the_ID());
//echo $loader->raceresult->getTable()->renderTable(get_post_meta(get_the_ID(),'bhaa_race_id',true));
?>
<hr/>

</div><!-- .entry-content -->

</div><!-- #bbp-user-->

</div><!-- #content -->

</div><!-- #container -->