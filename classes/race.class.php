<?php
class Race extends Base
{
	public $meta_fields = array( 'title', 'description', 'siteurl', 'category', 'post_tags' );
	
	/**
	 * https://github.com/emeraldjava/tmp-bmx/blob/master/controllers/events_controller.php
	 * 
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series1/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series2/
 	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series3/
 	 * 
	 */
	public function Race()
	{
		add_action( 'add_meta_boxes', array( &$this, 'raceMeta' ) );
		add_action( 'save_post', array( &$this, 'saveRaceMeta' ) );
		//add_filter( 'single_template',array( &$this,'race_single_template') );
		add_filter( 'single_template', array( &$this, 'get_custom_post_type_template') ) ;
		
		add_action( 'init', array(&$this,'getCPT'));
	}
// 		require_once( ABSPATH . 'wp-admin/includes/post.php' );
// 		register_post_type( 'race', getCPT() );
// 	}
	
	function get_custom_post_type_template($single_template) {
		global $post;
	
		if ($post->post_type == 'race') {
			$single_template = dirname( __FILE__ ) . '/single-race.php';
		}
		return $single_template;
	}
	

	
	public function getCPT()
	{
		$labels = array(
				'name' => _x( 'BHAA Races', 'race' ),
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
	
		$args = array(
				'labels' => $labels,
				'hierarchical' => false,
				'description' => 'bhaa race post',
				'supports' => array( 'title', 'editor'),// 'custom-fields', 'page-attributes' ),
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
		require_once( ABSPATH . 'wp-admin/includes/post.php' );
		register_post_type( 'race', $args );
	//		return $args;
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
			'side', 
			'low'
		);
	}
	
// 	function race_single_template($single_template) {
// 		global $post;
// 		if ($post->post_type == 'race')
// 			echo $this->loadTemplate('single-race');
// 		//$single_template = dirname( __FILE__ ) . '\templates\single-race.php';
// 		//return $single_template;
// 	}
	
	/**
	 * display the meta fields
	 * @param unknown_type $post
	 */
	public function raceMetaRender( $post ) {
		//wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
	
		$distance = get_post_custom_values('bhaa-race-distance', $post->ID);
		print '<p>Distance <input type="text" name="bhaa_race_distance" value="'.$distance[0].'" /></p>';
	
		$unit = get_post_custom_values('bhaa-race-unit', $post->ID);
		print '<p>Unit <input type="text" name="bhaa_race_unit" value="'.$unit[0].'" /></p>';
		
		$raceid = get_post_custom_values('bhaa-race-id', $post->ID);
		print '<p>Unit <input type="text" name="bhaa_race_id" value="'.$raceid[0].'" /></p>';
	}
	
	public function saveRaceMeta( $post_id ){
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
	
		if ( empty( $_POST ) )
			return;
	
		if ( !empty($_POST['bhaa_race_distance']))
			update_post_meta( $post_id, 'bhaa_race_distance', $_POST['bhaa-race-distance'] );
	
		if ( !empty($_POST['bhaa_race_unit']))
			update_post_meta( $post_id, 'bhaa_race_unit', $_POST['bhaa-race-unit'] );

		if ( !empty($_POST['bhaa_race_id']))
			update_post_meta( $post_id, 'bhaa_race_id', $_POST['bhaa-race-id'] );
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
	
	function listRaces($attr)
	{
		global $wpdb;
		$resultx = $wpdb->get_results($wpdb->prepare("SELECT id,event,distance,unit FROM ".$wpdb->race));
		return $this->loadTemplate('races',array('result' => $resultx));
	}
}
?>