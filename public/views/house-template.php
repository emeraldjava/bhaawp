<?php get_header(); ?>

<?php 
// use the template single-house instead

// This template gets the page content for a the house CPT.
$page = get_page_by_title('house_template_page');
$content = apply_filters('the_content', $page->post_content);
?>

<div class='container_wrap main_color <?php avia_layout_class( 'main' ); ?>'>

<div class='container'>

<div class='template-page content  <?php avia_layout_class( 'content' ); ?> units'>
<?php
echo $content;
?>
</div><!--end content-->

<?php 
//get the sidebar
$avia_config['currently_viewing'] = 'page';
get_sidebar();
?>
</div><!--end container-->
			
</div><!--end container-->
<?php get_footer(); ?>