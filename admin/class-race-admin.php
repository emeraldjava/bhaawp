<?php
/**
 * Race Admin Stuff
 * @author oconnellp
 */
class RaceAdmin {
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		// custom actions
		add_action('init',array($this,'bhaa_race_actions'),11);
		add_filter('post_row_actions', array(&$this,'bhaa_race_post_row_actions'), 0, 2);
		
		// custom admin columns
		add_filter('manage_race_posts_columns',array($this,'bhaa_manage_race_posts_columns'));
		add_filter('manage_race_posts_custom_column',array($this,'bhaa_manage_race_posts_custom_column'), 10, 3 );
	}
	
	function bhaa_race_post_row_actions($actions, $post) {
	
		if ($post->post_type =="race") {
			$actions = array_merge($actions, array(
				'bhaa_race_delete_results' => sprintf('<a href="%s">Delete Results</a>', 
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_delete_results&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_load_results' => sprintf('<a href="%s">Load Results</a>', 
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_load_results&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_update_positions' => sprintf('<a href="%s">Positions</a>', 
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_update_positions&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_update_pace' => sprintf('<a href="%s">Pace</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_update_pace&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_posincat' => sprintf('<a href="%s">Pos Cat</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_posincat&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_posinstd' => sprintf('<a href="%s">Pos Std</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_posinstd&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_postracestd' => sprintf('<a href="%s">Post Race Std</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_postracestd&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_league' => sprintf('<a href="%s">League</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_league&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_all' => sprintf('<a href="%s">BHAA ALL</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_all&post_id=%d', $post->ID),'bhaa')),
				'bhaa_team_results_delete' => sprintf('<a href="%s">Delete Team Results</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_team_results_delete&post_id=%d', $post->ID),'bhaa')),
				'bhaa_team_results_load' => sprintf('<a href="%s">Team Results</a>',
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_team_results_load&post_id=%d', $post->ID),'bhaa'))
			));
		}
		return $actions;
	}
	
	/**
	 * Filters for specific cpt actions.
	 */
	function bhaa_race_actions() {
		global $post_id;
		//$post_id = $_GET['post_id'];
		$raceResult = new RaceResult($post_id);
		
		switch ($_REQUEST['action']) {
			case "bhaa_race_delete_results":
				$raceResult->deleteRaceResults();
				queue_flash_message("bhaa_race_delete_results");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_load_results":
				$post = get_post($post_id);
				$results = explode("\n",$post->post_content);
				error_log('Number of rows '.sizeof($results));
				$n=0;
				foreach($results as $result)
				{
					// http://stackoverflow.com/questions/13430120/str-getcsv-alternative-for-older-php-version-gives-me-an-empty-array-at-the-e
					$details = explode(',',$result);
					$raceResult->addRaceResult($details);
					$n++;
					//if($n>=30)
						//break;
				}
				error_log('bhaa_race_load_results : '.$post_id);
				queue_flash_message("bhaa_race_load_results");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_update_positions":
				$raceResult->updatePositions();
				queue_flash_message("bhaa_race_update_positions");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_update_pace":
				$raceResult->updateRacePace();
				queue_flash_message("bhaa_race_update_pace");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_posincat":
				$raceResult->updateRacePosInCat();
				queue_flash_message("bhaa_race_posincat");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_posinstd":
				$raceResult->updateRacePosInStd();
				queue_flash_message("bhaa_race_posinstd");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_postracestd":
				$raceResult->updatePostRaceStd();
				queue_flash_message("bhaa_race_postracestd");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_league":
				$raceResult->updateLeague();
				queue_flash_message("bhaa_race_league");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_all":
				$raceResult->updateAll();
				queue_flash_message("bhaa_race_postracestd");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_team_results_delete":
				error_log('bhaa_team_results_delete');
				$teamResult = new TeamResult($post_id);
				$teamResult->deleteResults();
				queue_flash_message("bhaa_team_results_delete");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_team_results_load":
				$teamResult = new TeamResult($post_id);
				
				$teamResultBlob = get_post_meta($post_id,RaceCpt::BHAA_RACE_TEAM_RESULTS,true);
				//error_log('BLOB '.$teamResultBlob);
				
				$teamResults = explode("\n",$teamResultBlob);
				error_log('Number of team results '.sizeof($teamResults));
				$n=0;
				foreach($teamResults as $result)
				{
					$details = explode(',',$result);
					$teamResult->addResult($details);
					//$n++;
					//if($n==10)
						//break;
				}
				queue_flash_message("bhaa_team_results_load");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case 'bhaa-raceday-export':
				error_log("call bhaa-raceday-export admin");
				Raceday::get_instance()->export();
				exit();
				break;
		}
	}
	
	function bhaa_manage_race_posts_columns( $column ) {
		return array(
				'cb' => '<input type="checkbox" />',
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
}
?>