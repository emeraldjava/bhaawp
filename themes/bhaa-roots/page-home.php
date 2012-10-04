<?php
/*
 Template Name: BHAA Home
*/
?>

<?php get_header(); ?>

	<!-- events -->
	<section class="row">
	
		<div class="span6 columns">
			<h2>Next Event</h2>
			<?php echo do_shortcode('[events_list limit="3" orderby="event_start_date" order="ASC"][/events_list]');?>
		</div>
	
		<div class="span6 columns">
			<h2>Last Event</h2>
			<?php echo do_shortcode('[events_list limit="3" orderby="event_end_date" order="ASC"]#_EVENTLINK</br>[/events_list]');?>
		</div>
		
	</section>
	<!-- news houses -->	
	<section class="row">

			<div class="span6">
			
				<?php
					$args = array(			  		
						'post_type' => array('post'),
						'year' => '2012',
					);
					
					query_posts($args);
					
				?>
			
				<?php while (have_posts()) : the_post(); ?>
		    		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="post-<?php the_ID(); ?>-meta">Posted on: <?php the_time('F jS, Y') ?> | Posted in: <?php the_category(', ') ?><span><?php //comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></span></p>
						</header>
						<div class="entry-content">
							<?php the_excerpt(); ?>
						</div>
					</article>
				<?php endwhile; // End the loop ?>
			
			</div> <!-- eight columns -->
			
			<div class="span6">
			
				<?php
					$args = array(			  		
						'post_type' => array('house'),
						'year' => '2012',
					);
					
					query_posts($args);
					
				?>
			
				<?php while (have_posts()) : the_post(); ?>
		    		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="post-<?php the_ID(); ?>-meta">Posted on: <?php the_time('F jS, Y') ?> | Posted in: <?php the_category(', ') ?><span><?php //comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></span></p>
						</header>
						<div class="entry-content">
							<?php the_excerpt(); ?>
						</div>
					</article>
				<?php endwhile; // End the loop ?>
			
			</div> <!-- house end columns -->
		
		</div> <!-- row -->
	</section>
	
<?php get_footer(); ?>