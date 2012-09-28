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
	function House()
	{
		add_action( 'init', array(&$this,'register_taxonomy_sector'));
		add_action( 'init', array(&$this,'register_cpt_house'));
		add_filter( 'template_include', array(&$this,'house_templates'));
		
		// Add custom post navigation columns
		add_filter('manage_edit-house_columns', array(&$this, "nav_columns"));
		add_action('manage_posts_custom_column', array(&$this, "custom_nav_columns"));
		
		//add_action('admin_init', array(&$this, "admin_init"));
	}
	
	function admin_init(){
		add_meta_box("link-house-meta", "House Meta", array(&$this, "link_meta_box"), "link", "normal", "high");
	}
	
	function nav_columns($columns) {
		$columns = array(
				"cb" => "<input type=\"checkbox\" />",
				"title" => "Link Title",
				//"link_description" => "Description",
				"sector" => "Sector",
		);
	
		return $columns;
	}
	
	function custom_nav_columns($column) {
		global $post;
		switch ($column) {
// 			case "link_description":
// 				the_excerpt();
// 				break;
			case "link_sector":
				//$meta = get_taxpost_custom();
				echo get_taxonomies('sector','names');  //$meta["ch_link_url"][0];
				break;
		}
	}
	
	/**
	 * http://wordpress.stackexchange.com/questions/55763/is-it-possible-to-define-a-template-for-a-custom-post-type-within-a-plugin-indep
	 * @param unknown_type $template
	 */
	function house_templates( $template ) {
		$post_types = array( 'house' );
		if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-house.php' ) )
			$template = BHAAWP_PATH.'/template/archive-house.php';
		if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-house.php' ) )
			$template = BHAAWP_PATH.'/template/single-house.php';
		return $template;
	}
	
	// 
	function register_cpt_house() {
	
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
	        'supports' => array( 'title', 'editor', 'author', 'custom-fields','page-attributes' ),
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
		
	function register_taxonomy_sector() {
	
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