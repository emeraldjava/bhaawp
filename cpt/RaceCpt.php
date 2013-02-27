<?php
class RaceCpt
{
	private $race;

	const BHAA_RACE_DISTANCE = 'bhaa_race_distance';
	const BHAA_RACE_UNIT = 'bhaa_race_unit';
	const BHAA_RACE_TYPE = 'bhaa_race_type';

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
		add_action( 'save_post', array( &$this, 'bhaa_save_race_meta' ) );
	}
	
// 	function getRace()
// 	{
// 		if(!isset($this->race))
// 			$this->race = new Race();
// 		return $this->race;
// 	}
	

		
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

		$distance = get_post_custom_values(RaceCpt::BHAA_RACE_DISTANCE, $post->ID);
		print '<p>Distance <input type="text" name='.RaceCpt::BHAA_RACE_DISTANCE.' value="'.$distance[0].'" /></p>';

		$unit = get_post_custom_values(RaceCpt::BHAA_RACE_UNIT, $post->ID);
		print '<p>Unit <input type="text" name="'.RaceCpt::BHAA_RACE_UNIT.'" value="'.$unit[0].'" /></p>';

		$type = get_post_custom_values(RaceCpt::BHAA_RACE_TYPE, $post->ID);
		print '<p>Type <input type="text" name="'.BHAA_RACE_TYPE.'" value="'.$type[0].'" /></p>';

		//print '<p><input type="radio" name="'.RaceCpt::BHAA_RACE_ACTION.'" value="'.RaceCpt::BHAA_RACE_RELOAD.'">'.RaceCpt::BHAA_RACE_RELOAD.'</input><br/>';
		//print '<p><input type="radio" name="'.RaceCpt::BHAA_RACE_ACTION.'" value="'.RaceCpt::BHAA_RACE_DELETE.'">'.RaceCpt::BHAA_RACE_DELETE.'</input><br/>';
		//print '<p><input type="radio" name="'.RaceCpt::BHAA_RACE_ACTION.'" value="'.RaceCpt::BHAA_RACE_CALC.'">'.RaceCpt::BHAA_RACE_CALC.'</input><br/>';
			
		//print '<p>Last Action : '.$_SESSION['message'].'</p>';
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

		if ( !empty($_POST[RaceCpt::BHAA_RACE_DISTANCE]))
		{
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_DISTANCE, $_POST[RaceCpt::BHAA_RACE_DISTANCE] );
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST[RaceCpt::BHAA_RACE_DISTANCE]);
		}

		if ( !empty($_POST[RaceCpt::BHAA_RACE_UNIT]))
		{
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST[RaceCpt::BHAA_RACE_UNIT]);
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_UNIT, $_POST[RaceCpt::BHAA_RACE_UNIT] );
		}

		if ( !empty($_POST[RaceCpt::BHAA_RACE_TYPE]))
		{
			error_log($post_id .' -> '.RaceCpt::BHAA_RACE_TYPE.' -> '.$_POST[RaceCpt::BHAA_RACE_TYPE]);
			update_post_meta( $post_id, RaceCpt::BHAA_RACE_TYPE, $_POST[RaceCpt::BHAA_RACE_TYPE] );
		}

		//if ( !empty($_POST[RaceCpt::BHAA_RACE_ACTION]) )
		//{
			//$_SESSION['message'] = $_POST[RaceCpt::BHAA_RACE_ACTION].' Called';
			//error_log("race action ".$_POST[RaceCpt::BHAA_RACE_ACTION].' -> '.$post_id);
			//error_log('get_the_content() '.get_post($post_id)->post_content);
			//$this->raceresult->processRaceResults(get_post($post_id)->post_content);
			//$this->raceresult->deleteRaceResults($post_id);
			//$this->insert_csv_action(RaceCpt::BHAA_RACE_RESULTS_RELOAD,$_POST[RaceCpt::BHAA_RACE_RESULTS_RELOAD]);
		//}
	}
}
?>