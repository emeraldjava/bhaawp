<?php
class Company
{
	function Company()
	{
		add_action( 'init', array(&$this,'register_cpt_company'));
	}
	
	// http://themergency.com/generators/wordpress-custom-post-types/
	function register_cpt_company() {
	
	    $labels = array( 
	        'name' => _x( 'Companies', 'company' ),
	        'singular_name' => _x( 'Company', 'company' ),
	        'add_new' => _x( 'Add New', 'company' ),
	        'add_new_item' => _x( 'Add New Company', 'company' ),
	        'edit_item' => _x( 'Edit Company', 'company' ),
	        'new_item' => _x( 'New Company', 'company' ),
	        'view_item' => _x( 'View Company', 'company' ),
	        'search_items' => _x( 'Search Companies', 'company' ),
	        'not_found' => _x( 'No companies found', 'company' ),
	        'not_found_in_trash' => _x( 'No companies found in Trash', 'company' ),
	        'parent_item_colon' => _x( 'Parent Company:', 'company' ),
	        'menu_name' => _x( 'Companies', 'company' ),
	    );
	
	    $args = array( 
	        'labels' => $labels,
	        'hierarchical' => false,
	        'description' => 'BHAA Company Details',
	        'supports' => array( 'title', 'editor', 'author', 'custom-fields', 'comments' ),
	        'taxonomies' => array( 'category', 'post_tag', 'sector' ),
	        'public' => true,
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'show_in_nav_menus' => true,
	        'publicly_queryable' => true,
	        'exclude_from_search' => false,
	        'has_archive' => true,
	        'query_var' => true,
	        'can_export' => true,
	        'rewrite' => true,
	        'capability_type' => 'post'
	    );
	    register_post_type( 'company', $args );
	}
		
		
		
// 		function register_taxonomy_sector() {
		
// 			$labels = array(
// 					'name' => _x( 'sectors', 'sector' ),
// 					'singular_name' => _x( 'sector', 'sector' ),
// 					'search_items' => _x( 'Search sectors', 'sector' ),
// 					'popular_items' => _x( 'Popular sectors', 'sector' ),
// 					'all_items' => _x( 'All sectors', 'sector' ),
// 					'parent_item' => _x( 'Parent sector', 'sector' ),
// 					'parent_item_colon' => _x( 'Parent sector:', 'sector' ),
// 					'edit_item' => _x( 'Edit sector', 'sector' ),
// 					'update_item' => _x( 'Update sector', 'sector' ),
// 					'add_new_item' => _x( 'Add New sector', 'sector' ),
// 					'new_item_name' => _x( 'New sector', 'sector' ),
// 					'separate_items_with_commas' => _x( 'Separate sectors with commas', 'sector' ),
// 					'add_or_remove_items' => _x( 'Add or remove sectors', 'sector' ),
// 					'choose_from_most_used' => _x( 'Choose from most used sectors', 'sector' ),
// 					'menu_name' => _x( 'sectors', 'sector' ),
// 			);
		
// 			$args = array(
// 					'labels' => $labels,
// 					'public' => true,
// 					'show_in_nav_menus' => true,
// 					'show_ui' => true,
// 					'show_tagcloud' => true,
// 					'hierarchical' => false,
		
// 					'rewrite' => true,
// 					'query_var' => true
// 			);
		
// 			register_taxonomy( 'sector', array('post'), $args );
// 		}
}
?>