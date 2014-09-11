<?php get_header(); ?>
<section id="primary">
<?php 
// This template gets the page content for a the house CPT.
$page = get_page_by_title('house_template_page');
$content = apply_filters('the_content', $page->post_content);
echo $content;
?>
</section>