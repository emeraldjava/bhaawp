<?php
get_header();

echo '<section id="primary">';
echo '<h1>bhaa wp team template league</h1>';

echo '<h2>League Summary</h2>';
echo do_shortcode('[one_half last="no"][bhaa_league division=M top=10][/one_half]');
echo do_shortcode('[one_half last="yes"][bhaa_league division=W top=10][/one_half]');
echo '</section>';
echo do_shortcode('[separator top="40" style="single"]');

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>