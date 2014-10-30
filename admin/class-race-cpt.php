<?php
class RaceCpt {
	
	const BHAA_RACE_DISTANCE = 'bhaa_race_distance';
	const BHAA_RACE_UNIT = 'bhaa_race_unit';
	const BHAA_RACE_TYPE = 'bhaa_race_type';
	const BHAA_RACE_TEAM_RESULTS = 'bhaa_race_team_results';

	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		add_action( 'init', array(&$this,'bhaa_register_race_cpt'));

		// custom meta
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_race_meta_data' ) );
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_team_meta_data' ) );
		
		add_action( 'save_post', array( &$this, 'bhaa_save_race_meta' ) );
		
		// custom admin columns
		add_filter('manage_race_posts_columns',array($this,'bhaa_manage_race_posts_columns'));
		add_filter('manage_race_posts_custom_column',array($this,'bhaa_manage_race_posts_custom_column'), 10, 3 );
		
		add_filter('post_row_actions', array(&$this,'bhaa_race_post_row_actions'), 0, 2);
		
		// custom admin actions
		add_action('admin_action_bhaa_race_delete_results',array(&$this,'bhaa_race_delete_results'));
		add_action('admin_action_bhaa_race_load_results',array(&$this,'bhaa_race_load_results'));
		add_action('admin_action_bhaa_race_positions',array(&$this,'bhaa_race_positions'));
		add_action('admin_action_bhaa_race_pace',array(&$this,'bhaa_race_pace'));
		add_action('admin_action_bhaa_race_pos_in_cat',array(&$this,'bhaa_race_pos_in_cat'));
		add_action('admin_action_bhaa_race_pos_in_std',array(&$this,'bhaa_race_pos_in_std'));
		add_action('admin_action_bhaa_race_update_standards',array(&$this,'bhaa_race_update_standards'));
		add_action('admin_action_bhaa_race_league',array(&$this,'bhaa_race_league'));
		add_action('admin_action_bhaa_race_all',array(&$this,'bhaa_race_all'));
		add_action('admin_action_bhaa_race_delete_team_results',array(&$this,'bhaa_race_delete_team_results'));
		add_action('admin_action_bhaa_race_load_team_results',array(&$this,'bhaa_race_load_team_results'));
		add_action('admin_action_bhaa_race_add_result',array(&$this,'bhaa_race_add_result'));
		add_action('admin_action_bhaa_raceday_export',array(&$this,'bhaa_raceday_export'));
	}
	
	function bhaa_race_delete_results() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->deleteRaceResults();
		queue_flash_message("bhaa_race_delete_results ".$_GET['post_id']);
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_load_results() {
		$raceResult = new RaceResult($_GET['post_id']);
		$post = get_post($_GET['post_id']);
		$resultText = $post->post_content;
		error_log('bhaa_race_load_results('.strlen($resultText).')');
		$results = explode("\n",$resultText);
		error_log('Number of rows '.sizeof($results));
		foreach($results as $result)
		{
			// http://stackoverflow.com/questions/13430120/str-getcsv-alternative-for-older-php-version-gives-me-an-empty-array-at-the-e
			$details = explode(',',$result);
			$raceResult->addRaceResult($details);
			$n++;
			//if($n>=30)
			//break;
		}
		queue_flash_message('bhaa_race_load_results '.sizeof($results));
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_positions() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updatePositions();
		queue_flash_message("bhaa_race_positions");
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	function bhaa_race_pace() {
		error_log("GET  :".print_r($_GET,true));
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updateRacePace();
		queue_flash_message("bhaa_race_pace ".$_GET['post_id']);
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	function bhaa_race_pos_in_cat() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updateRacePosInCat();
		queue_flash_message("bhaa_race_pos_in_cat");
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_pos_in_std() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updateRacePosInStd();
		queue_flash_message("bhaa_race_pos_in_std");
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_update_standards() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updatePostRaceStd();
		queue_flash_message("bhaa_race_update_standards");
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_league() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updateLeague();
		queue_flash_message("bhaa_race_league");
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_all() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->updateAll();
		queue_flash_message("bhaa_race_all");
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_delete_team_results(){
		error_log('bhaa_race_delete_team_results');
		$teamResult = new TeamResult($_GET['post_id']);
		$teamResult->deleteResults();
		queue_flash_message("bhaa_race_delete_team_results");
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_race_load_team_results() {
		$teamResult = new TeamResult($_GET['post_id']);
		$teamResultBlob = get_post_meta($_GET['post_id'],RaceCpt::BHAA_RACE_TEAM_RESULTS,true);
		$teamResults = explode("\n",$teamResultBlob);
		error_log('Number of team results '.sizeof($teamResults));
		foreach($teamResults as $result){
			$details = explode(',',$result);
			$teamResult->addResult($details);
		}
		queue_flash_message("bhaa_race_load_team_results :: ".sizeof($teamResults));
		wp_redirect(wp_get_referer());
		exit();
	}
	function bhaa_raceday_export() {
		Raceday::get_instance()->export();
		exit();
	}
	function bhaa_race_add_result() {
		$raceResult = new RaceResult($_GET['post_id']);
		$raceResult->addDefaultResult();
		queue_flash_message("bhaa_race_add_result");
		wp_redirect(wp_get_referer());
		exit();
	}

	function bhaa_race_post_row_actions($actions, $post) {
		if ($post->post_type =="race") {
			$actions = array_merge($actions,$this->get_admin_url_links($post));
		}
		return $actions;
	}
		
	public function bhaa_register_race_cpt() {
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
			'supports' => array('title','excerpt','editor'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'exclude_from_search' => false,
			'has_archive' => false,
			'query_var' => true,
			'can_export' => true,
			'publicly_queryable' => true,
			'rewrite' =>  true, // array('slug' => 'race','with_front' => false),
			'capability_type' => 'post'
		);
		register_post_type( 'race', $raceArgs );
	}
	
	function bhaa_manage_race_posts_columns( $column ) {
		return array(
			'title' => __('Title'),
			'distance' => __('Distance'),
			'type' => __('Type'),
			'date' => __('Date')
		);
	}
	
	function bhaa_manage_race_posts_custom_column( $column, $post_id ) {
		switch ($column) {
			case 'distance' :
				echo get_post_meta($post_id,RaceCpt::BHAA_RACE_DISTANCE,true).''.get_post_meta($post_id,RaceCpt::BHAA_RACE_UNIT,true);
				break;
			case 'type' :
				echo get_post_meta($post_id,RaceCpt::BHAA_RACE_TYPE,true);
				break;
			default:
		}
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
			'high'
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

	private function get_admin_url_links($post) {
		return array(
			'bhaa_race_delete_results' => $this->generate_admin_url_link('bhaa_race_delete_results',$post->ID,'Delete Results'),
			'bhaa_race_load_results' => $this->generate_admin_url_link('bhaa_race_load_results',$post->ID,'Load Results'),
			'bhaa_race_positions' => $this->generate_admin_url_link('bhaa_race_positions',$post->ID,'Positions'),
			'bhaa_race_pace' => $this->generate_admin_url_link('bhaa_race_pace',$post->ID,'Pace'),
			'bhaa_race_pos_in_cat' => $this->generate_admin_url_link('bhaa_race_pos_in_cat',$post->ID,'Pos_in_cat'),
			'bhaa_race_pos_in_std' => $this->generate_admin_url_link('bhaa_race_pos_in_std',$post->ID,'Pos_in_std'),
			'bhaa_race_update_standards' => $this->generate_admin_url_link('bhaa_race_update_standards',$post->ID,'Update Stds'),
			'bhaa_race_league' => $this->generate_admin_url_link('bhaa_race_league',$post->ID,'League Points'),
			'bhaa_race_all' => $this->generate_admin_url_link('bhaa_race_all',$post->ID,'All'),
			'bhaa_race_delete_team_results' => $this->generate_admin_url_link('bhaa_race_delete_team_results',$post->ID,'Delete Teams'),
			'bhaa_race_load_team_results' => $this->generate_admin_url_link('bhaa_race_load_team_results',$post->ID,'Load Teams'),
			'bhaa_race_add_result' => $this->generate_admin_url_link('bhaa_race_add_result',$post->ID,'Add Result')
		);
	}
	
	/**
	 * Use the admin.php page as the hook point
	 * http://shibashake.com/wordpress-theme/obscure-wordpress-errors-why-where-and-how
	 */
	private function generate_admin_url_link($action,$post_id,$name) {
		$nonce = wp_create_nonce( $action );
		$link = admin_url('admin.php?action='.$action.'&post_type=race&post_id='.$post_id);
		return '<a href='.$link.'>'.$name.'</a>';
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
		
		echo implode('<br/>', $this->get_admin_url_links($post));
	}

	public function bhaa_race_team_result_textarea( $post ) {
		$teamresults = get_post_meta($post->ID,RaceCpt::BHAA_RACE_TEAM_RESULTS,true);
		echo '<textarea name='.RaceCpt::BHAA_RACE_TEAM_RESULTS.' id='.RaceCpt::BHAA_RACE_TEAM_RESULTS.'
			 rows="20" cols="80" style="width:99%">'.$teamresults.'</textarea>';
		//echo '<textarea rows="20" cols="80" name='.RaceCpt::BHAA_RACE_TEAM_RESULTS.' value="'.$teamresults.'" />';
	}
	
	/**
	 * Save the race meta data
	 * @param unknown_type $post_id
	 */
	public function bhaa_save_race_meta( $post_id ) {
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