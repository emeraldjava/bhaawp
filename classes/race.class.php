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
		//self::$instance = $this;
		add_action( 'admin_init', array(&$this, 'admin_init') ); // this must be first
		add_action( 'template_redirect', array(&$this, 'template_redirect') );
		add_action( 'wp_insert_post', array(&$this, 'wp_insert_post'), 10, 2 );
	}
	
	public function admin_init()
	{
		// http://codex.wordpress.org/Function_Reference/add_meta_box
		add_meta_box( "race-meta", "BHAA Race", array( &$this, "meta_options" ), "race", "side", "low" );
	}
	
	public function meta_options()
	{
		echo '<label for="bhaa_race_unit">';
		//	_e("Description for this field", 'myplugin_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="distance" name="distance" value="distance" size="25" />';
		echo '<input type="text" id="unit" name="unit" value="unit" size="25" />';
	}
	
	public function getCPT()
	{
		$labels = array(
				'name' => _x( 'races', 'race' ),
				'singular_name' => _x( 'race', 'race' ),
				'add_new' => _x( 'Add New', 'race' ),
				'add_new_item' => _x( 'Add New race', 'race' ),
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
				'rewrite' => array('slug' => 'race'),
				'capability_type' => 'post'
		);
		return $args;
	}
	
	/**
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series1/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series2/
	 * http://new2wp.com/pro/wordpress-custom-post-types-object-oriented-series3/
	 */
	// Template redirect for custom templates
	public function template_redirect() {
		global $wp_query;
// 		if ($wp_query->query_vars['post_type'] == 'race') {
// 			include(TEMPLATEPATH . '/single-race.php'); // a custom single-slug.php template
// 			die();
// 		} else {
// 			$wp_query->is_404 = true;
// 		}
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