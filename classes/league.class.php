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
 * @author oconnellp
 *
 */
class League
{
	function League()
	{
		//add_action( 'init', array(&$this,'register_taxonomy_sector'));
		add_action( 'init', array(&$this,'registerLeagueCPT'));
		add_action( 'wp_loaded', array(&$this,'leagueConnectionTypes'));
		add_filter( 'template_include', array(&$this,'league_templates'));
	}
	
	function leagueConnectionTypes() {
		// Make sure the Posts 2 Posts plugin is active.
		//require_once( ABSPATH . 'wp-content/plugins/posts-to-posts/core/api.php' );
		if ( !function_exists( 'p2p_register_connection_type' ) )
			return;
	
		p2p_register_connection_type( array(
				'name' => 'league_to_events',
				'from' => 'league',
				'to' => 'event',
				'cardinality' => 'one-to-many'
		) );
	}
	
	/**
	 * http://wordpress.stackexchange.com/questions/55763/is-it-possible-to-define-a-template-for-a-custom-post-type-within-a-plugin-indep
	 * @param unknown_type $template
	 */
	function league_templates( $template ) {
		$post_types = array( 'league' );
		if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-league.php' ) )
			$template = BHAAWP_PATH.'/template/archive-league.php';
		if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-league.php' ) )
			$template = BHAAWP_PATH.'/template/single-league.php';
		return $template;
	}
	
	// 
	function registerLeagueCPT() 
	{
	    $leagueLabels = array( 
	        'name' => _x( 'Leagues', 'league' ),
	        'singular_name' => _x( 'League', 'league' ),
	        'add_new' => _x( 'Add New', 'league' ),
	        'add_new_item' => _x( 'Add New League', 'league' ),
	        'edit_item' => _x( 'Edit League', 'league' ),
	        'new_item' => _x( 'New League', 'league' ),
	        'view_item' => _x( 'View League', 'league' ),
	        'search_items' => _x( 'Search Leagues', 'league' ),
	        'not_found' => _x( 'No league found', 'league' ),
	        'not_found_in_trash' => _x( 'No leagues found in Trash', 'league' ),
	        'parent_item_colon' => _x( 'Parent League:', 'league' ),
	        'menu_name' => _x( 'Companies', 'league' ),
	    );
	
	    $leagueArgs = array( 
	        'labels' => $leagueLabels,
	        'hierarchical' => false,
	        'description' => 'BHAA League Details',
	        'supports' => array( 'title', 'author'),
	        //'taxonomies' => array( 'sector' ),
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
	    register_post_type( 'league', $leagueArgs );
	}
}
?>