<?php
/**
 * The Race class register a Custom Post Type to track race meta details.
 * 
 * The bhaa meta fields are tracked via a metabox, and raceresults can be imported once
 * via csv string. The details are inserted into RaceResult details.
 * 
 * The custom page view reused the WP_List_Table to provide a view of the results.
 *  
 * http://www.netmagazine.com/tutorials/user-friendly-custom-fields-meta-boxes-wordpress
 * @author oconnellp
 *
 */
class Race
{
	const BHAA_RACE_DISTANCE = 'bhaa_race_distance';
	const BHAA_RACE_UNIT = 'bhaa_race_unit';
	const BHAA_RACE_TYPE = 'bhaa_race_type';
	const BHAA_RACE_RESULTS_RELOAD = 'bhaa_race_results_reload';
	const BHAA_RACE_RESULTS_DELETE = 'bhaa_race_results_delete';
	
	/**
	 * https://github.com/emeraldjava/tmp-bmx/blob/master/controllers/events_controller.php
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series1/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series2/
 	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series3/
 	 * 
	 */
	public function Race()
	{
		add_action( 'init', array(&$this,'register_race_cpt'));
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_race_meta_data' ) );
		add_action( 'save_post', array( &$this, 'bhaa_save_race_meta' ) );
		
		// add custom post actions and handlers
		add_filter('manage_race_posts_columns',array($this,'bhaa_manage_race_posts_columns'));
		add_filter('manage_race_posts_custom_column',array($this,'bhaa_manage_race_posts_custom_column'), 10, 3 );
	}
	
	function bhaa_manage_race_posts_columns( $column ) {
		return array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'distance' => __('Distance'),
			'type' => __('Type'),
			'date' => __('Date')
		);
		// merge column
		//return array_merge($column,array('sector' => __('Sector')));
	}
	
	function bhaa_manage_race_posts_custom_column( $column, $post_id )
	{
		switch ($column) {
			case 'distance' :
				echo get_post_meta($post_id,Race::BHAA_RACE_DISTANCE,true).''.get_post_meta($post_id,Race::BHAA_RACE_UNIT,true);
				break;
			case 'type' :
				echo get_post_meta($post_id,Race::BHAA_RACE_TYPE,true);
				break;
			default:
		}
		return $return;
	}
			
	public function register_race_cpt()
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
			'public' => false,
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
	
	/**
	 * display the meta fields
	 * http://www.netmagazine.com/tutorials/user-friendly-custom-fields-meta-boxes-wordpress
	 * @param unknown_type $post
	 */
	public function bhaa_race_meta_fields( $post ) {
		//wp_nonce_field( plugin_basename( __FILE__ ), 'bhaa_race_meta_data' );
	
		$distance = get_post_custom_values(Race::BHAA_RACE_DISTANCE, $post->ID);
		print '<p>Distance <input type="text" name='.Race::BHAA_RACE_DISTANCE.' value="'.$distance[0].'" /></p>';
	
		$unit = get_post_custom_values(Race::BHAA_RACE_UNIT, $post->ID);
		print '<p>Unit <input type="text" name="'.Race::BHAA_RACE_UNIT.'" value="'.$unit[0].'" /></p>';
		
		$type = get_post_custom_values(Race::BHAA_RACE_TYPE, $post->ID);
		print '<p>Type <input type="text" name="'.BHAA_RACE_TYPE.'" value="'.$type[0].'" /></p>';
		
		echo '<p>Reload <input type="radio" name="'.Race::BHAA_RACE_RESULTS_RELOAD.'" value="Y">Y</input>'.
			'<input type="radio" name="'.Race::BHAA_RACE_RESULTS_RELOAD.'" value="N">N</input></p>';
		
		echo '<p>Delete <input type="radio" name="'.Race::BHAA_RACE_RESULTS_DELETE.'" value="Y">Y</input>'.
			'<input type="radio" name="'.Race::BHAA_RACE_RESULTS_DELETE.'" value="N">N</input></p>';
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
	
		if ( !empty($_POST[Race::BHAA_RACE_DISTANCE]))
		{
			update_post_meta( $post_id, Race::BHAA_RACE_DISTANCE, $_POST[Race::BHAA_RACE_DISTANCE] );
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST[Race::BHAA_RACE_DISTANCE]);
		}
		
		if ( !empty($_POST[Race::BHAA_RACE_UNIT]))
		{
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST[Race::BHAA_RACE_UNIT]);
			update_post_meta( $post_id, Race::BHAA_RACE_UNIT, $_POST[Race::BHAA_RACE_UNIT] );
		}
		
		if ( !empty($_POST[Race::BHAA_RACE_TYPE]))
		{
			error_log($post_id .' -> '.Race::BHAA_RACE_TYPE.' -> '.$_POST[Race::BHAA_RACE_TYPE]);
			update_post_meta( $post_id, Race::BHAA_RACE_TYPE, $_POST[Race::BHAA_RACE_TYPE] );
		}
		
		if ( !empty($_POST[Race::BHAA_RACE_RESULTS_RELOAD]))
		{
			$this->csv_action(Race::BHAA_RACE_RESULTS_RELOAD,$_POST[Race::BHAA_RACE_RESULTS_RELOAD]);
		}
		
		if ( !empty($_POST[Race::BHAA_RACE_RESULTS_DELETE]))
		{
			$this->csv_action(Race::BHAA_RACE_RESULTS_DELETE,$_POST[Race::BHAA_RACE_RESULTS_DELETE]);
		}
	}
		
	function csv_action($action,$value)
	{
		error_log('csv_action -> '.$action.' -> '.$_POST[$action].' : '.$value);			
	}
}
?>