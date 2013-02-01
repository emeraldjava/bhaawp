<?php
class HouseCpt
{
	const TEAM_TYPE = 'teamtype';
	const COMPANY_TEAM = 'companyteam';
	const SECTOR_TEAM = 'sectorteam';
	const INACTIVE_TEAM = 'inactiveteam';
	
	function __construct()
	{
		add_action( 'init', array(&$this,'bhaa_register_cpt_house'));
		add_action( 'init', array(&$this,'bhaa_register_taxonomy_sector'));
		add_action( 'init', array(&$this,'bhaa_register_taxonomy_teamtype'));
	
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
	
	function bhaa_register_cpt_house()
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
				'supports' => array( 'title','editor','excerpt','thumbnail','comments'),
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
	
	function bhaa_register_taxonomy_sector()
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
	
	function bhaa_register_taxonomy_teamtype()
	{
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
		wp_insert_term(
			HouseCpt::INACTIVE_TEAM, // the term
			'teamtype', // the taxonomy
			array(
				'description'=> 'Holder for houses with inactive teams.',
				'slug' => HouseCpt::INACTIVE_TEAM,
				'parent'=> $parent_term_id)
		);
	}
}
?>