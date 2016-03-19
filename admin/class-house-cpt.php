<?php
class HouseCpt {
	
	const TEAM_TYPE = 'teamtype';
	const COMPANY_TEAM = 'company';
	const SECTOR_TEAM = 'sector';
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		add_action( 'init', array(&$this,'bhaa_register_cpt_house'));
		add_action( 'init', array(&$this,'bhaa_register_taxonomy_sector'));
		add_action( 'init', array(&$this,'bhaa_register_taxonomy_teamtype'));
		//add_action( 'init', array(&$this,'bhaa_register_taxonomy_teamstatus'));
		
		// display the admin status column
		add_filter('manage_house_posts_columns',array($this,'bhaa_manage_house_posts_columns'));
		add_filter('manage_house_posts_custom_column',array($this,'bhaa_manage_house_posts_custom_column'), 10, 3 );
	}
			
 	function bhaa_manage_house_posts_columns( $columns ) {
 		return array(
 				'cb' => '<input type="checkbox" />',
 				'title' => __('Title'),
 				'sector' => __('Sector'),
 				'active' => __('Active Runners'),
 				'total' => __('Total Runners'),
 				'date' => __('Date')
 		);
 	}
 		
 	function bhaa_manage_house_posts_custom_column( $column, $post_id ) {
 		global $wpdb;
 		switch ($column) {
 			case 'sector' :
 				echo get_the_term_list( $post_id, 'sector','','','');
 				break;
			case 'total' :
				$total = $wpdb->get_var(
					 $wpdb->prepare(
					 	"select count(p2p_to) from wp_p2p where p2p_from=%d and p2p_type='house_to_runner'",$post_id));
				echo $total;
				break;
			case 'active' :
				$active = $wpdb->get_var(
					$wpdb->prepare(
						"select count(p2p_to) from wp_p2p
				join wp_usermeta status ON (status.user_id=wp_p2p.p2p_to AND status.meta_key = 'bhaa_runner_status' and status.meta_value='M')
				where p2p_from=%d and p2p_type='house_to_runner'",$post_id));
				echo $active;
				break;
 			default:
 		}
 		return $return;
 	}
	
	function bhaa_register_cpt_house() {
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
				'hierarchical' => true,
				'description' => 'BHAA House Details',
				'supports' => array('title','editor','excerpt','thumbnail','comments'),// add 'page-attributes' for parent hierarchy
				'taxonomies' => array('sector','category'),
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
				'capability_type' => 'post',
				'show_in_rest' => true,
				'rest_controller_class' => 'WP_REST_Posts_Controller'
		);
		register_post_type( 'house', $houseArgs );
	}
	
	function bhaa_register_taxonomy_sector() {
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
	
	function bhaa_register_taxonomy_teamtype() {
		$labels = array(
				'name' => _x( 'Team Types', 'teamtype' ),
				'singular_name' => _x( 'Team Type', 'teamtype' ),
				'search_items' => _x( 'Search teamtypes', 'teamtype' ),
				'popular_items' => _x( 'Popular teamtypes', 'teamtype' ),
				'all_items' => _x( 'All teamtypes', 'teamtype' ),
				'parent_item' => _x( 'Parent teamtype', 'teamtype' ),
				'parent_item_colon' => _x( 'Parent teamtype:', 'teamtype' ),
				'edit_item' => _x( 'Edit teamtype', 'teamtype' ),
				'update_item' => _x( 'Update teamtype', 'teamtype' ),
				'add_new_item' => _x( 'Add New teamtype', 'teamtype' ),
				'new_item_name' => _x( 'New teamtype', 'teamtype' ),
				'separate_items_with_commas' => _x( 'Separate teamtypes with commas', 'teamtype' ),
				'add_or_remove_items' => _x( 'Add or remove teamtypes', 'teamtype' ),
				'choose_from_most_used' => _x( 'Choose from most used teamtypes', 'teamtype' ),
				'menu_name' => _x( 'Team Types', 'teamtype' ),
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
		register_taxonomy( 'teamtype', array('house'), $args );
		
		// register the three types
		$parent_term = term_exists( 'teamtype', 'house' );
		$parent_term_id = $parent_term['term_id']; // get numeric term id
		wp_insert_term(
			HouseCpt::COMPANY_TEAM, // the term
			'teamtype', // the taxonomy
			array(
				'description'=> 'A company with many runners',
				'slug' => HouseCpt::COMPANY_TEAM,
				'parent'=> $parent_term_id)
		);
		wp_insert_term(
			HouseCpt::SECTOR_TEAM, // the term
			'teamtype', // the taxonomy
			array(
				'description'=> 'Runners from companies in the same sector, limited to 6.',
				'slug' => HouseCpt::SECTOR_TEAM,
				'parent'=> $parent_term_id)
		);
	}
	
	function bhaa_register_taxonomy_teamstatus() {
		$labels = array(
				'name' => _x( 'TeamStatus', 'teamstatus' ),
				'singular_name' => _x( 'TeamStatus', 'teamstatus' ),
				'search_items' => _x( 'Search teamstatuss', 'teamstatus' ),
				'popular_items' => _x( 'Popular teamstatuss', 'teamstatus' ),
				'all_items' => _x( 'All teamstatuss', 'teamstatus' ),
				'parent_item' => _x( 'Parent teamstatus', 'teamstatus' ),
				'parent_item_colon' => _x( 'Parent teamstatus:', 'teamstatus' ),
				'edit_item' => _x( 'Edit teamstatus', 'teamstatus' ),
				'update_item' => _x( 'Update teamstatus', 'teamstatus' ),
				'add_new_item' => _x( 'Add New teamstatus', 'teamstatus' ),
				'new_item_name' => _x( 'New teamstatus', 'teamstatus' ),
				'separate_items_with_commas' => _x( 'Separate teamstatus with commas', 'teamstatus' ),
				'add_or_remove_items' => _x( 'Add or remove teamstatus', 'teamstatus' ),
				'choose_from_most_used' => _x( 'Choose from most used teamstatus', 'teamstatus' ),
				'menu_name' => _x( 'TeamStatus', 'teamstatus' ),
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
		register_taxonomy( House::TEAM_STATUS, array(House::HOUSE), $args );
	
		// register the three types
		$parent_term = term_exists(House::TEAM_STATUS,House::HOUSE);
		$parent_term_id = $parent_term['term_id']; // get numeric term id
		wp_insert_term(
			House::ACTIVE, // the term
			House::TEAM_STATUS, // the taxonomy
			array(
			'description'=> 'An active team',
			'slug' => House::ACTIVE,
			'parent'=> $parent_term_id)
		);
		wp_insert_term(
			House::PENDING, // the term
			House::TEAM_STATUS, // the taxonomy
			array(
			'description'=> 'Pending Team.',
			'slug' => House::PENDING,
			'parent'=> $parent_term_id)
		);
	}
	
	/**
	 * http://wp.tutsplus.com/articles/tips-articles/quick-tip-make-your-custom-column-sortable/
	 */
	function bhaa_manage_edit_house_sortable_columns($columns) {
		$columns['active'] = 'active';
		$columns['total'] = 'total';
		//To make a column 'un-sortable' remove it from the array
		unset($columns['date']);
		return $columns;
	}
}
?>