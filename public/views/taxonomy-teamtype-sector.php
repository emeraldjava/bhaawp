<?php get_header(); ?>
	<div id="content" class="portfolio portfolio-three portfolio-three-text" style="width:100%">
		<div class="portfolio-wrapper">
		<?php
		// http://stackoverflow.com/questions/7688591/query-posts-by-custom-taxonomy-id
		$the_query = new WP_Query(array(
				'post_type' => 'house',
				'showposts' => -1,
				'post_status '=>'publish',
				'tax_query' => array(
						array(
								'taxonomy' => 'teamtype',
								'terms' => 'sector',
								'field' => 'slug',
						)
				),
				'orderby' => 'title',
				'order' => 'ASC'
			)
		);
		?>
		<?php while($the_query->have_posts()): $the_query->the_post(); ?>
		<div class="portfolio-item">
			<div id="post-<?php the_ID(); ?>" class="portfolio-content" >
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php
					$users = get_users( array(
						'connected_type' => 'team_contact',
						'connected_items' => get_the_ID(),
						'suppress_filters' => false,
						'nopaging' => true
					));
					$user = get_user_by('id', $users[0]->ID);
					echo '<h4><i>Team Contact :</i> '.$user->display_name.'</h5>';
					
					$runners = get_users( array(
						'connected_type' => 'sectorteam_to_runner',
						'connected_items' => get_the_ID()
					));
					echo '<h5><i>Number of Runners :</i> '.sizeof($runners).'</h5>';	
					?>

				<div class="post-content"><?php the_excerpt(); ?></div>
			</div><!-- portfolio-content -->
		</div><!-- portfolio-item -->
		<?php endwhile; ?>
		</div> <!-- portfolio-wrapper -->
	</div>
	<?php 
		$args = array(
				'smallest'                  => 12,
				'largest'                   => 16,
				'unit'                      => 'pt',
				'number'                    => 90,
				'format'                    => 'flat',
				//   'separator'                 => \\"\n\\",
				'orderby'                   => 'name',
				'order'                     => 'ASC',
				'exclude'                   => '5',
				//    'include'                   => null,
				//    'topic_count_text_callback' => default_topic_count_text,
				//    'link'                      => 'view',
				'taxonomy'                  => 'teamtype',
				'echo'                      => true );
		//wp_tag_cloud($args);
	?>
<?php get_footer(); ?>