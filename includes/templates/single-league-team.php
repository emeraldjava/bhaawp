<?php
get_header();

echo '<section id="primary">';
echo the_title('<h1>','</h1>');
echo do_shortcode('[av_one_half first][bhaa_league division=M top=10][/av_one_half]');
echo do_shortcode('[av_one_half][bhaa_league division=W top=10][/av_one_half]');
echo '</section>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>