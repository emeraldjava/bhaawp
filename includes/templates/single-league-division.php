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
//echo  post_permalink($post);
//echo get_permalink();
//echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
//echo '<a href="'.post_permalink($post).'">Back to '.post_title($post).'</a>';
echo '<div id="division'.$wp_query->query_vars['division'].'">Back to <a href="'.get_permalink().'">'.get_the_title().'</a> Division '.$wp_query->query_vars['division'].'</div>';
echo do_shortcode('[bhaa_league division='.$wp_query->query_vars['division'].' top=1000]');
echo '</div></div></div>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>