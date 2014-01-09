<?php
//http://codex.wordpress.org/Function_Reference/wp_tag_cloud
get_header();
?>

<div id="primary" class="site-content">
	<div id="content" role="main">


		<?php 
		$args = array(
				'smallest'                  => 12,
				'largest'                   => 22,
				'unit'                      => 'pt',
				'number'                    => 45,
				'format'                    => 'flat',
				//   'separator'                 => \\"\n\\",
				'orderby'                   => 'name',
				'order'                     => 'ASC',
				'exclude'                   => '5',
				//    'include'                   => null,
				//    'topic_count_text_callback' => default_topic_count_text,
				//    'link'                      => 'view',
				'taxonomy'                  => 'sector',
				'echo'                      => true );
		wp_tag_cloud($args);
		?>

	</div>
	<!-- #content -->
</div>
<!-- #primary -->