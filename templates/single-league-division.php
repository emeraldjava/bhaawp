<?php
get_header();

echo '<section id="primary">';
echo  post_permalink($post);
//echo the_title('<h1>','</h1>');
echo do_shortcode('[bhaa_league division=A top=1000]');
echo '</section>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>