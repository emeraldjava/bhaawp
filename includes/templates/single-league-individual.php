<?php
get_header();

echo '<section id="primary">';
echo the_title('<h1>','</h1>');

echo do_shortcode('[av_one_third first][av_textblock][bhaa_league division=A top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=B top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=L1 top=10][/av_textblock][/av_one_third]');

echo do_shortcode('[av_one_third first][av_textblock][bhaa_league division=C top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=D top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=L2 top=10][/av_textblock][/av_one_third]');

echo do_shortcode('[av_one_half first][av_textblock][bhaa_league division=E top=10][/av_textblock][/av_one_half]');
echo do_shortcode('[av_one_half ][av_textblock][bhaa_league division=F top=10][/av_textblock][/av_one_half]');

//echo do_shortcode('[separator top="40" style="single"]');
echo '</section>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>