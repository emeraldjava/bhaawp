<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->
					BHAA Company Name <?php echo the_title();?> ID : <?php echo the_ID();?> 
					
					<?php 
					//echo '<img src="'.get_post_meta(get_the_ID(),'bhaa_company_image',true).'" width="40%" height="100px"/>'
					?>

					<?php
// Find connected posts
// https://github.com/scribu/wp-posts-to-posts/wiki/Posts-2-Users
$users = get_users( array(
		'connected_type' => 'company_to_runner',
		'connected_items' => $post
) );
?>

<h4><?php echo get_the_term_list( the_ID(), 'sector', 'Sector: ', ', ', ''); ?></h4>
<h3>Runners :</h3>
<ul>
<?php foreach ( $users AS $user ) : ?>
	<li><?php echo $user->display_name; ?></li>
<?php endforeach; ?>
</ul>

<?php 
// Prevent weirdness
//wp_reset_postdata();
//endforeach;
?>


					<?php get_template_part( 'content', 'single' ); ?>
					
					<?php //comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>