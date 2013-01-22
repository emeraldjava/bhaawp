<?php
class LeagueCpt
{
	function __construct()
	{
		add_action( 'init', array(&$this,'registerLeagueCPT'));
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
			'menu_name' => _x( 'BHAA Leagues', 'league' ),
			);
	
		$leagueArgs = array(
			'labels' => $leagueLabels,
			'hierarchical' => false,
			'description' => 'BHAA League Details',
			'supports' => array( 'title','editor','excerpt','thumbnail','custom-fields','page-attributes','comments'),
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
		register_post_type('league', $leagueArgs );
	}
}
?>