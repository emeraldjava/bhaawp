<?php
get_header();

echo '<section id="primary">';
echo '<h1>bhaa wp individual league template</h1>';

echo '<h2>League Summary</h2>';
echo do_shortcode('[one_third last="no"][bhaa_league division=A top=10][/one_third]');
echo do_shortcode('[one_third last="no"][bhaa_league division=B top=10][/one_third]');
echo do_shortcode('[one_third last="yes"][bhaa_league division=L1 top=10][/one_third]');

echo do_shortcode('[one_third last="no"][bhaa_league division=C top=10][/one_third]');
echo do_shortcode('[one_third last="no"][bhaa_league division=D top=10][/one_third]');
echo do_shortcode('[one_third last="yes"][bhaa_league division=L2 top=10][/one_third]');

echo do_shortcode('[one_half last="no"][bhaa_league division=E top=10][/one_half]');
echo do_shortcode('[one_half last="yes"][bhaa_league division=F top=10][/one_half]');

echo do_shortcode('[separator top="40" style="single"]');

echo '</section>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>