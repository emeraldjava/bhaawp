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
	const bhaa_race_csv = 'bhaa_race_csv';
	public $meta_fields = array( 'title', 'description', 'siteurl', 'category', 'post_tags' );
	
	/**
	 * https://github.com/emeraldjava/tmp-bmx/blob/master/controllers/events_controller.php
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series1/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series2/
 	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series3/
 	 * 
	 */
	public function Race()
	{
		add_action( 'init', array(&$this,'getCPT'));
		add_action( 'add_meta_boxes', array( &$this, 'raceMeta' ) );
		add_action( 'save_post', array( &$this, 'saveRaceMeta' ) );
		add_filter( 'template_include', array( &$this, 'race_templates') ) ;
	}
	
	/**
	 * use a custom post template.
	 * @param unknown_type $template
	 */
	function race_templates( $template ) {
		$post_types = array( 'race' );
		if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-race.php' ) )
			$template = BHAAWP_PATH.'/template/archive-race.php';
		if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-race.php' ) )
			$template = BHAAWP_PATH.'/template/single-race.php';
		return $template;
	}
		
	public function getCPT()
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
			'menu_name' => _x( 'races', 'race' ),
		);
	
		$raceArgs = array(
			'labels' => $raceLabels,
			'hierarchical' => false,
			'description' => 'bhaa race post',
			'supports' => array('title','excerpt'),// 'custom-fields', 'page-attributes' ),
			//'register_meta_box_cb' => 'raceMeta',
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => 'race',
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
	public function raceMeta() {
		add_meta_box(
			'bhaa-race-meta',
			__( 'BHAA Race Details', 'bhaa-race-meta' ),
			array(&$this, 'raceMetaRender'),
			'race',
			'advanced', 
			'low'
		);
	}
		
	/**
	 * display the meta fields
	 * http://www.netmagazine.com/tutorials/user-friendly-custom-fields-meta-boxes-wordpress
	 * @param unknown_type $post
	 */
	public function raceMetaRender( $post ) {
		//wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
	
		$distance = get_post_custom_values('bhaa_race_distance', $post->ID);
		print '<p>Distance <input type="text" name="bhaa_race_distance" value="'.$distance[0].'" /></p>';
	
		$unit = get_post_custom_values('bhaa_race_unit', $post->ID);
		print '<p>Unit <input type="text" name="bhaa_race_unit" value="'.$unit[0].'" /></p>';
		
		$raceid = get_post_custom_values('bhaa_race_id', $post->ID);
		print '<p>ID <input type="text" name="bhaa_race_id" value="'.$raceid[0].'" /></p>';
		
		$csv = get_post_custom_values(bhaa_race_csv, $post->ID);
		print '<p>CSV<textarea cols=100 rows=20 name="bhaa_race_csv">'.$csv.'</textarea></p>';
	}
	
	/**
	 * Save the race meta data
	 * @param unknown_type $post_id
	 */
	public function saveRaceMeta( $post_id ){
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
	
		if ( empty( $_POST ) )
			return;
	
		if ( !empty($_POST['bhaa_race_distance']))
		{
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST['bhaa_race_distance']);
			update_post_meta( $post_id, 'bhaa_race_distance', $_POST['bhaa_race_distance'] );
		}
			
		if ( !empty($_POST[bhaa_race_csv]))
		{
			error_log($post_id .' -> bhaa_race_csv -> '.$_POST[bhaa_race_csv]);
			update_post_meta( $post_id, bhaa_race_csv, $_POST[bhaa_race_csv] );
		}
		
		if ( !empty($_POST['bhaa_race_id']))
			update_post_meta( $post_id, 'bhaa_race_id', $_POST['bhaa_race_id'] );
		
		if ( !empty($_POST['bhaa_race_distance']))
		{
			error_log($post_id .' -> bhaa_race_distance -> '.$_POST['bhaa_race_distance']);
			update_post_meta( $post_id, 'bhaa_race_distance', $_POST['bhaa_race_distance'] );
		}
	}
		
	/**
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series1/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series2/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series3/
	 */
	// Template redirect for custom templates
	public function template_redirect() {
		global $wp_query;
	}
	
	// For inserting posts
	public function wp_insert_post($post_id, $post = null) {
		if ($post->post_type == "race") {
			error_log("bhaa.race.wp_insert_post");
			foreach ($this->meta_fields as $key) {
				$value = @$_POST[$key];
				if (empty($value)) {
					delete_post_meta($post_id, $key);
					continue;
				}
				if (!is_array($value)) {
					if (!update_post_meta($post_id, $key, $value)) {
						error_log('bhaa wp_insert_post '.$post_id.' '.$key.' '.$value);
						add_post_meta($post_id, $key, $value);
					}
				} else {
					delete_post_meta($post_id, $key);
					foreach ($value as $entry) add_post_meta($post_id, $key, $entry);
				}
			}
		}
	}
}
?>