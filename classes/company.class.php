<?php
/**
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
 * 
 * @author oconnellp
 *
 */
class Company
{
	function Company()
	{
		add_action( 'init', array(&$this,'register_taxonomy_sector'));
		add_action( 'init', array(&$this,'register_cpt_company'));
		add_filter( 'template_include', array(&$this,'company_templates'));
	}
	
	/**
	 * http://wordpress.stackexchange.com/questions/55763/is-it-possible-to-define-a-template-for-a-custom-post-type-within-a-plugin-indep
	 * @param unknown_type $template
	 */
	function company_templates( $template ) {
		$post_types = array( 'company' );
		if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-company.php' ) )
			$template = BHAAWP_PATH.'/template/archive-company.php';
		if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-company.php' ) )
			$template = BHAAWP_PATH.'/template/single-company.php';
		return $template;
	}
	
	// 
	function register_cpt_company() {
	
	    $companyLabels = array( 
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
	        'menu_name' => _x( 'BHAA Companies', 'company' ),
	    );
	
	    $companyArgs = array( 
	        'labels' => $companyLabels,
	        'hierarchical' => false,
	        'description' => 'BHAA Company Details',
	        'supports' => array( 'title', 'editor', 'author', 'custom-fields', 'comments' ),
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
	    register_post_type( 'company', $companyArgs );
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
		register_taxonomy( 'sector', array('company'), $args );
// 		wp_insert_term('Media','sector',array('description'=> 'Media'));
// 		wp_insert_term('Banking','sector',array('description'=> 'Banking'));
// 		wp_insert_term('IT','sector',array('description'=> 'IT nerds','slug' => 'IT'));
	}
}
?>