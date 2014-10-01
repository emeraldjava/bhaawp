<?php
get_header();

echo '<div class="container">';
echo '<div class="content">';
//echo  post_permalink($post);
//echo get_permalink();
//echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
//echo '<a href="'.post_permalink($post).'">Back to '.post_title($post).'</a>';
echo '<div id="division'.$wp_query->query_vars['division'].'">Back to <a href="'.get_permalink().'">'.get_the_title().'</a> Division '.$wp_query->query_vars['division'].'</div>';
echo do_shortcode('[bhaa_league division='.$wp_query->query_vars['division'].' top=1000]');
echo '</div></div>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>