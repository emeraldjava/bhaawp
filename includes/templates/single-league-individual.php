<?php
get_header();

echo '<div class="stretch_full container_wrap alternate_color light_bg_color title_container">
<div class="container">';
echo the_title('<h1 class="main-title entry-title">','</h1>');
echo '<div class="breadcrumb breadcrumbs avia-breadcrumbs">
<div xmlns:v="http://rdf.data-vocabulary.org/#" class="breadcrumb-trail">
<span class="trail-before"><span class="breadcrumb-title">
You are here:</span></span> <span typeof="v:Breadcrumb">
<a class="trail-begin" title="Business Houses Athletic Association Dublin" href="http://bhaa.ie" property="v:title" rel="v:url">Home</a></span> <span class="sep">/</span> <span typeof="v:Breadcrumb"><span class="trail-end">Leagues</span></span></div></div></div></div>';
echo '<div class="container_wrap container_wrap_first main_color fullsize">';

echo '<div class="container">';
echo '<div class="content">';

echo do_shortcode('[av_one_third first][av_textblock][bhaa_league division=A top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=B top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=L1 top=10][/av_textblock][/av_one_third]');

echo do_shortcode('[av_one_third first][av_textblock][bhaa_league division=C top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=D top=10][/av_textblock][/av_one_third]');
echo do_shortcode('[av_one_third ][av_textblock][bhaa_league division=L2 top=10][/av_textblock][/av_one_third]');

echo do_shortcode('[av_one_half first][av_textblock][bhaa_league division=E top=10][/av_textblock][/av_one_half]');
echo do_shortcode('[av_one_half ][av_textblock][bhaa_league division=F top=10][/av_textblock][/av_one_half]');

echo do_shortcode('[av_one_full first]
[av_notification title="" color=custom border=solid custom_bg="#012C52" custom_font="#ffffff" size="normal"]Comments[/av_notification]
[av_comments_list]
[/av_one_full]');

echo '</div></div>';
echo '</div></div>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>