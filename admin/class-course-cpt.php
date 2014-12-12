<?php
/**
 * A course post type for map and route description
 * @author oconnellp
 */
class CourseCpt {

	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
		
	private function __construct() {
		add_action('init',array(&$this,'register_course_cpt'));
	}	
			
	/**
	 * Register the course CPT
	 */
	function register_course_cpt() {
		$courseLabels = array(
			'name' => _x( 'Courses', 'course' ),
			'singular_name' => _x( 'Course', 'course' ),
			'add_new' => _x( 'Add New', 'course' ),
			'add_new_item' => _x( 'Add New Course', 'course' ),
			'edit_item' => _x( 'Edit Course', 'course' ),
			'new_item' => _x( 'New Course', 'course' ),
			'view_item' => _x( 'View Course', 'course' ),
			'search_items' => _x( 'Search Courses', 'course' ),
			'not_found' => _x( 'No course found', 'course' ),
			'not_found_in_trash' => _x( 'No couses found in Trash', 'course' ),
	        'parent_item_colon' => _x( 'Parent Course:', 'course' ),
			'menu_name' => _x( 'BHAA Leagues', 'course' ),
			);
	
		$courseArgs = array(
			'labels' => $courseLabels,
			'hierarchical' => false,
			'description' => 'BHAA Course Details',
			'supports' => array( 'title','editor','excerpt','thumbnail'),
			'taxonomies' => array('category'),// post_tag
        	'public' => true,
        	'show_ui' => true,
        	'show_in_menu' => true,
        	'show_in_nav_menus' => true,
        	'publicly_queryable' => false,
	        'exclude_from_search' => true,
	        'has_archive' => false,
	        'query_var' => false,
	        'can_export' => false,
	        'rewrite' => true,
	        'capability_type' => 'post'
	    );
		register_post_type('course', $courseArgs);
	}
}
?>