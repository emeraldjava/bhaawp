<?php
class LeagueCpt
{
	const BHAA_LEAGUE_RACES_TO_SCORE = 'races_to_score';
	const BHAA_LEAGUE_TYPE = 'bhaa_league_type';
	
	function __construct()
	{
		add_action('init',array(&$this,'registerLeagueCPT'));
		add_action('init',array(&$this,'bhaa_league_actions'),11);
		add_filter('post_row_actions', array(&$this,'bhaa_league_post_row_actions'), 0, 2);
		
		// custom meta
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_league_meta_data' ) );
		add_action( 'save_post', array( &$this, 'bhaa_league_save_meta_data' ) );
	}
	
	public function bhaa_league_meta_data() {
		add_meta_box(
			'bhaa_league_meta',
			__( 'League Details', 'bhaa_league_meta' ),
			array(&$this, 'bhaa_league_meta_data_fields'),
			'league',
			'side',
			'low'
		);
	}
	
	function bhaa_league_meta_data_fields( $post ) {
		//wp_nonce_field( plugin_basename( __FILE__ ), 'bhaa_race_meta_data' );
	
		$races_to_score = get_post_custom_values(LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE, $post->ID);
		echo '<p>Races To Score <input type="text" name='.LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE.' value="'.$races_to_score[0].'" /></p>';
	
		// http://stackoverflow.com/questions/3507042/if-block-inside-echo-statement
		$type = get_post_custom_values(LeagueCpt::BHAA_LEAGUE_TYPE, $post->ID);
		echo '<p>Type <select name='.LeagueCpt::BHAA_LEAGUE_TYPE.'>';
		echo '<option value="I" '.(($type[0]=='I')?'selected="selected"':"").'>I</option>';
		echo '<option value="T" '.(($type[0]=='T')?'selected="selected"':"").'>T</option>';
		echo '</select></p>';
	}
	
	public function bhaa_league_save_meta_data( $post )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
	
		if ( empty( $_POST ) )
			return;
	
		if ( !empty($_POST[LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE])) {
			update_post_meta( $post, LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE, $_POST[LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE] );
		}
		if ( !empty($_POST[LeagueCpt::BHAA_LEAGUE_TYPE])) {
			update_post_meta( $post, LeagueCpt::BHAA_LEAGUE_TYPE, $_POST[LeagueCpt::BHAA_LEAGUE_TYPE]);
		}
	}
	/**
	 * http://wordpress.stackexchange.com/questions/8481/custom-post-row-actions
	 * http://wordpress.org/support/topic/trying-to-add-custom-post_row_actions-for-custom-post-status
	 * http://wordpress.stackexchange.com/questions/14973/row-actions-for-custom-post-types
	 * http://wordpress.org/support/topic/replacement-for-post_row_actions
	 * http://www.ilovecolors.com.ar/saving-custom-fields-quick-bulk-edit-wordpress/
	 */
	
	function bhaa_league_post_row_actions($actions, $post) {
		if ($post->post_type =="league") {
			$actions = array_merge($actions, array(
				'update_league' => sprintf('<a href="%s">Update League</a>',
					wp_nonce_url(
						sprintf('edit.php?post_type=league&action=bhaa_update_league_data&post_id=%d', $post->ID),'bhaa_update_league_data'.$post->ID))
			));
		}
		return $actions;
	}

	/**
	 * Filters for specific cpt actions.
	 */
	function bhaa_league_actions() {
		if(isset($_GET['action']) 
			&& ($_GET['action'] == 'bhaa_update_league_data') 
			&& wp_verify_nonce($_GET['_wpnonce'],'bhaa_update_league_data'.$_GET['post_id']) ) {
				$id = $_GET['post_id'];
				error_log('bhaa_update_league_data : '.$id.' '.$action);
				$leagueSummary = new LeagueSummary($id);
				$leagueSummary->updateLeagueData();
				wp_redirect(wp_get_referer()); 
				exit();
		}
	}
	
	//
	function registerLeagueCPT() {
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
			'supports' => array( 'title','editor','excerpt','thumbnail','comments'),
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
			'has_archive' => true,
	        'capability_type' => 'post'
	        );
		register_post_type('league', $leagueArgs );
		
		$labels = array(
			'name' => _x( 'Divisions', 'division' ),
			'singular_name' => _x( 'Division', 'division' ),
			'search_items' => _x( 'Search divisions', 'division' ),
			'popular_items' => _x( 'Popular divisions', 'division' ),
			'all_items' => _x( 'All divisions', 'division' ),
			'parent_item' => _x( 'Parent division', 'division' ),
			'parent_item_colon' => _x( 'Parent division:', 'division' ),
			'edit_item' => _x( 'Edit division', 'division' ),
			'update_item' => _x( 'Update division', 'division' ),
			'add_new_item' => _x( 'Add New division', 'division' ),
			'new_item_name' => _x( 'New division', 'division' ),
			'separate_items_with_commas' => _x( 'Separate divisions with commas', 'division' ),
			'add_or_remove_items' => _x( 'Add or remove divisions', 'division' ),
			'choose_from_most_used' => _x( 'Choose from most used divisions', 'division' ),
			'menu_name' => _x( 'Divisions', 'division' ),
		);
	}
}
?>