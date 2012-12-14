<?php
/**
 * House will handle the whole company, sector and teams madness.
 * 
 * http://themergency.com/generators/wordpress-custom-post-types
 * 
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 * http://codex.wordpress.org/Function_Reference/wp_insert_term
 * 
 * http://wp.tutsplus.com/tutorials/theme-development/innovative-uses-of-wordpress-post-types-and-taxonomies/
 * 
 * ajax search widget
 * http://wordpress.stackexchange.com/questions/62720/ajax-search-on-post-pages-by-custom-post-type
 * http://wpsnipp.com/index.php/template/create-multiple-search-templates-for-custom-post-types/
 * http://thomasgriffinmedia.com/blog/2010/11/how-to-include-custom-post-types-in-wordpress-search-results/
 * http://curtishenson.com/wordpress-custom-post-types-and-meta-boxes-example/
 * @author oconnellp
 *
 */
class House
{
	const BHAA_HOUSE_TYPE = 'bhaa_house_type';
	const COMPANY = 'company';
	const SECTORTEAM = 'sectorteam';
	
	function House()
	{
		add_action( 'init', array(&$this,'register_taxonomy_sector'));
		add_action( 'init', array(&$this,'register_cpt_house'));
		
		// display the admin status column
		add_filter('manage_house_posts_columns',array($this,'bhaa_manage_house_posts_columns'));
		add_filter('manage_house_posts_custom_column',array($this,'bhaa_manage_house_posts_custom_column'), 10, 3 );
	}
	
	function bhaa_manage_house_posts_columns( $column ) {
		return array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'sector' => __('Sector'),
			'date' => __('Date')
		);
		// merge column
		//return array_merge($column,array('sector' => __('Sector')));
	}
	
	function bhaa_manage_house_posts_custom_column( $column, $post_id )
	{
		switch ($column) {
			case 'sector' :
				echo get_the_term_list( $post_id, 'sector','','','');
				break;
			default:
		}
		return $return;
	}
				
	function register_cpt_house() 
	{
	    $houseLabels = array( 
	        'name' => _x( 'Houses', 'house' ),
	        'singular_name' => _x( 'House', 'house' ),
	        'add_new' => _x( 'Add New', 'house' ),
	        'add_new_item' => _x( 'Add New House', 'house' ),
	        'edit_item' => _x( 'Edit House', 'house' ),
	        'new_item' => _x( 'New House', 'house' ),
	        'view_item' => _x( 'View House', 'house' ),
	        'search_items' => _x( 'Search Houses', 'house' ),
	        'not_found' => _x( 'No houses found', 'house' ),
	        'not_found_in_trash' => _x( 'No houses found in Trash', 'house' ),
	        'parent_item_colon' => _x( 'Parent House:', 'house' ),
	        'menu_name' => _x( 'BHAA Houses', 'house' ),
	    );
	
	    $houseArgs = array( 
	        'labels' => $houseLabels,
	        'hierarchical' => false,
	        'description' => 'BHAA House Details',
	        'supports' => array( 'title','editor','excerpt','thumbnail','custom-fields','page-attributes','comments'),
	        'taxonomies' => array( 'sector' ),
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
	    register_post_type( 'house', $houseArgs );
	}
		
	function register_taxonomy_sector() 
	{
		$labels = array(
			'name' => _x( 'Sectors', 'sector' ),
			'singular_name' => _x( 'Sector', 'sector' ),
			'search_items' => _x( 'Search sectors', 'sector' ),
			'popular_items' => _x( 'Popular sectors', 'sector' ),
			'all_items' => _x( 'All sectors', 'sector' ),
			'parent_item' => _x( 'Parent sector', 'sector' ),
			'parent_item_colon' => _x( 'Parent sector:', 'sector' ),
			'edit_item' => _x( 'Edit sector', 'sector' ),
			'update_item' => _x( 'Update sector', 'sector' ),
			'add_new_item' => _x( 'Add New sector', 'sector' ),
			'new_item_name' => _x( 'New sector', 'sector' ),
			'separate_items_with_commas' => _x( 'Separate sectors with commas', 'sector' ),
			'add_or_remove_items' => _x( 'Add or remove sectors', 'sector' ),
			'choose_from_most_used' => _x( 'Choose from most used sectors', 'sector' ),
			'menu_name' => _x( 'Sectors', 'sector' ),
		);
	
		$args = array(
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => false,
				'rewrite' => true,
				'query_var' => true
		);
		register_taxonomy( 'sector', array('house'), $args );
	}
}
?>