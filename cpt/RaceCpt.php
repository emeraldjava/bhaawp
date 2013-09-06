<?php
class RaceCpt
{
	private $race;

	const BHAA_RACE_DISTANCE = 'bhaa_race_distance';
	const BHAA_RACE_UNIT = 'bhaa_race_unit';
	const BHAA_RACE_TYPE = 'bhaa_race_type';
	const BHAA_RACE_TEAM_RESULTS = 'bhaa_race_team_results';

	/**
	 * https://github.com/emeraldjava/tmp-bmx/blob/master/controllers/events_controller.php
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series1/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series2/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series3/
	 *
	 */
	public function __construct()
	{
		add_action( 'init', array(&$this,'bhaa_register_race_cpt'));

		// custom meta
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_race_meta_data' ) );
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_team_meta_data' ) );
		
		add_action( 'save_post', array( &$this, 'bhaa_save_race_meta' ) );
	}
		
	public function bhaa_register_race_cpt()
	{
		$raceLabels = array(
				'name' => _x( 'Races', 'race' ),
				'singular_name' => _x( 'Race', 'race' ),
				'add_new' => _x( 'Add New', 'race' ),
				'add_new_item' => _x( 'Add New Race', 'race' ),
				'edit_item' => _x( 'Edit race', 'race' ),
				'new_item' => _x( 'New race', 'race' ),
				'view_item' => _x( 'View race', 'race' ),
				'search_items' => _x( 'Search races', 'race' ),
				'not_found' => _x( 'No races found', 'race' ),
				'not_found_in_trash' => _x( 'No races found in Trash', 'race' ),
				'parent_item_colon' => _x( 'Parent event:', 'event' ),
				'menu_name' => _x( 'BHAA Races', 'race' ),
		);

		$raceArgs = array(
				'labels' => $raceLabels,
				'hierarchical' => false,
				'description' => 'bhaa race post',
				'supports' => array('title','excerpt','editor','post-formats'),// 'custom-fields', 'page-attributes' ),
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => true,
				'publicly_queryable' => false,
				'exclude_from_search' => true,
				'has_archive' => true,
				'query_var' => true,
				'can_export' => true,
				'publicly_queryable' => true,
				'rewrite' => true, //array('slug' => 'race'),
				'capability_type' => 'post'
		);
		register_post_type( 'race', $raceArgs );
	}

	/**
	 * Register the race meta box
	 */
	public function bhaa_race_meta_data() {
		add_meta_box(
			'bhaa-race-meta',
			__( 'Race Details', 'bhaa-race-meta' ),
			array(&$this, 'bhaa_race_meta_fields'),
			'race',
			'side',
			'low'
		);	
	}
	
	public function bhaa_team_meta_data() {
		add_meta_box(
			'bhaa-race-team-meta',
			__( 'Team Results', 'bhaa-race-team-meta' ),
			array(&$this, 'bhaa_race_team_result_textarea'),
			'race',
			'normal',
			'high'
		);
	}

	/**
	 * display the meta fields
	 * http://www.netmagazine.com/tutorials/user-friendly-custom-fields-meta-boxes-wordpress
	 * @param unknown_type $post
	 */
	public function bhaa_race_meta_fields( $post ) {
		//wp_nonce_field( plugin_basename( __FILE__ ), 'bhaa_race_meta_data' );

		$distance = get_post_custom_values(RaceCpt::BHAA_RACE_DISTANCE, $post->ID);
		echo '<p>Distance <input type="text" name='.RaceCpt::BHAA_RACE_DISTANCE.' value="'.$distance[0].'" /></p>';

		// http://stackoverflow.com/questions/3507042/if-block-inside-echo-statement
		$unit = get_post_custom_values(RaceCpt::BHAA_RACE_UNIT, $post->ID);
		echo '<p>Unit <select name='.RaceCpt::BHAA_RACE_UNIT.'>';
		echo '<option value="Mile" '.(($unit[0]=='Mile')?'selected="selected"':"").'>Mile</option>';
		echo '<option value="Km" '.(($unit[0]=='Km')?'selected="selected"':"").'>Km</option>';
		echo '</select></p>';

		$type = get_post_custom_values(RaceCpt::BHAA_RACE_TYPE, $post->ID);
		echo '<p>Type <select name='.RaceCpt::BHAA_RACE_TYPE.'>';
		echo '<option value="C" '.(($type[0]=='C')?'selected="selected"':"").'>C</option>';
		echo '<option value="M" '.(($type[0]=='M')?'selected="selected"':"").'>M</option>';
		echo '<option value="W" '.(($type[0]=='W')?'selected="selected"':"").'>W</option>';
		echo '<option value="S" '.(($type[0]=='S')?'selected="selected"':"").'>S</option>';
		echo '<option value="TRACK" '.(($type[0]=='TRACK')?'selected="selected"':"").'>TRACK</option>';
		echo '</select></p>';
		
		// admin url links		
		echo sprintf('<a href="%s">Delete Results</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_delete_results&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Load Results</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_load_results&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Positions</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_update_positions&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Pace</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_update_pace&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Pos Cat</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_posincat&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Pos Std</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_posinstd&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Post Race Std</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_postracestd&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">League</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_league&post_id=%d', $post->ID),'bhaa'));		
		echo sprintf('<a href="%s">BHAA ALL</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_all&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Delete Team Results</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_team_results_delete&post_id=%d', $post->ID),'bhaa'));
		echo sprintf('<a href="%s">Load Team Results</a><br/>',wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_team_results_load&post_id=%d', $post->ID),'bhaa'));
		
	}

	public function bhaa_race_team_result_textarea( $post ) {
		$teamresults = get_post_meta($post->ID, RaceCpt::BHAA_RACE_TEAM_RESULTS,true);
		echo '<textarea name='.RaceCpt::BHAA_RACE_TEAM_RESULTS.' id='.RaceCpt::BHAA_RACE_TEAM_RESULTS.'
			 rows="20" cols="80" style="width:99%">'.$teamresults.'</textarea>';
		//echo '<textarea rows="20" cols="80" name='.RaceCpt::BHAA_RACE_TEAM_RESULTS.' value="'.$teamresults.'" />';
	}
	
	/**
	 * Save the race meta data
	 * @param unknown_type $post_id
	 */
	public function bhaa_save_race_meta( $post_id ){
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( empty( $_POST ) )
			return;

		if ( !empty($_POST[RaceCpt::BHAA_RACE_DISTANCE])) {
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_DISTANCE, $_POST[RaceCpt::BHAA_RACE_DISTANCE] );
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST[RaceCpt::BHAA_RACE_DISTANCE]);
		}

		if ( !empty($_POST[RaceCpt::BHAA_RACE_UNIT])) {
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST[RaceCpt::BHAA_RACE_UNIT]);
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_UNIT, $_POST[RaceCpt::BHAA_RACE_UNIT] );
		}

		if ( !empty($_POST[RaceCpt::BHAA_RACE_TYPE])) {
			error_log($post_id .' -> '.RaceCpt::BHAA_RACE_TYPE.' -> '.$_POST[RaceCpt::BHAA_RACE_TYPE]);
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_TYPE, $_POST[RaceCpt::BHAA_RACE_TYPE] );
		}
		
		if ( !empty($_POST[RaceCpt::BHAA_RACE_TEAM_RESULTS])) {
			error_log($post_id .' -> '.RaceCpt::BHAA_RACE_TEAM_RESULTS.' -> '.$_POST[RaceCpt::BHAA_RACE_TEAM_RESULTS]);
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_TEAM_RESULTS, $_POST[RaceCpt::BHAA_RACE_TEAM_RESULTS] );
		}
	}
}
?>