<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); 
?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->
					BHAA League
					
					
<?php
					
// https://github.com/scribu/wp-posts-to-posts/wiki/Basic-usage

// Find connected pages
$connected = new WP_Query( array(
  'connected_type' => 'league_to_event',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
) );

// Display connected pages
if ( $connected->have_posts() ) :
?>
<h3>Events in League</h3>
<ul>
<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
	<?php 
	//$EM_Event = new EM_Events( array( 'post_id'=>the_ID() ) );
	
	$EM_Event = em_get_event(the_ID(),'post_id');
	//$EM_Event = new EM_Event(the_ID());
	//echo var_dump($EM_Event);
	echo 'date '.$EM_Event->event_start_date;
		
	echo get_post_type(); ?>
	<li><a href="<?php the_permalink(); ?>"><?php the_title().' '.the_ID(); ?></a></li>
<?php endwhile; ?>
</ul>

<?php 
// Prevent weirdness
wp_reset_postdata();

endif;
?>
					
					<?php get_template_part( 'content', 'single' ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>