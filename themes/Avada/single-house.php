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

	<!-- http://wpsnipp.com/index.php/template/create-multiple-search-templates-for-custom-post-types/ -->
	<!-- http://www.studionashvegas.com/development/search-specific-post-type-wordpress/ -->
	<!-- HIDE AJAX SEARCH
	<div class="ui-widget">
		<label for="humm">Search:</label><input id="house_search" />
	</div>-->
				
					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->
					
					<?php get_template_part( 'content', 'single' ); ?>
										
					<img src="<?php echo get_post_meta(get_the_ID(),'bhaa_company_image',true); ?>" width="40%" height="100px"/>'
					<br/>
					<?php
// Find connected posts
// https://github.com/scribu/wp-posts-to-posts/wiki/Posts-2-Users
$users = get_users( array(
	'connected_type' => Connection::HOUSE_TO_RUNNER,
	'connected_items' => $post
) );
?>

<h4><?php echo get_the_term_list( the_ID(), 'sector', 'Sector: ', ', ', ''); ?></h4>
<h3>Runners :</h3>
<br/>
<ul>
<?php foreach ( $users AS $user ) : ?>
	<li><?php 
	$page = get_page_by_title('runner');
	//echo $user->display_name.'-'.$user->ID;
	echo sprintf('<a href="/?page_id=%d&id=%d">%s</a>',$page->ID,$user->ID,$user->display_name); ?></li>
<?php endforeach; ?>
</ul>

<?php 
// Prevent weirdness
//wp_reset_postdata();
//endforeach;
?>
	
	
	

					
					<?php //comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>