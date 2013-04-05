<?php
/**
 * Race Admin Stuff
 * @author oconnellp
 */
class RaceAdmin
{
	function __construct()
	{
		// custom actions
		add_action( 'init', array(&$this,'bhaa_race_actions'),11);
		add_filter('post_row_actions', array(&$this,'bhaa_race_post_row_actions'), 0, 2);
		
		// custom admin columns
		add_filter('manage_race_posts_columns',array($this,'bhaa_manage_race_posts_columns'));
		add_filter('manage_race_posts_custom_column',array($this,'bhaa_manage_race_posts_custom_column'), 10, 3 );
	}
	
	function bhaa_race_post_row_actions($actions, $post) {
	
		if ($post->post_type =="race")
		{
			$actions = array_merge($actions, array(
				'bhaa_race_delete_results' => sprintf('<a href="%s">Delete Results</a>', 
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_delete_results&post_id=%d', $post->ID),'bhaa')),
				'bhaa_race_load_results' => sprintf('<a href="%s">Load Results</a>', 
					wp_nonce_url(sprintf('edit.php?post_type=race&action=bhaa_race_load_results&post_id=%d', $post->ID),'bhaa')),
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
	function bhaa_race_actions()
	{
		$post_id = $_GET['post_id'];
		$race = new RaceResult($post_id);
		
		switch ($_REQUEST['action']) {
			case "bhaa_race_delete_results":
				$race->deleteRaceResults();
				queue_flash_message("bhaa_race_delete_results");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_load_results":
				$post = get_post($post_id);
				$results = explode("\n",$post->post_content);
				error_log('Number of rows '.sizeof($results));
				foreach($results as $result)
				{
					// http://stackoverflow.com/questions/13430120/str-getcsv-alternative-for-older-php-version-gives-me-an-empty-array-at-the-e
					$details = explode(',',$result);
					$race->addRaceResult($details);
				}
				error_log('bhaa_race_load_results : '.$post_id);
				queue_flash_message("bhaa_race_load_results");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_update_pace":
				$race->updateRacePace();
				queue_flash_message("bhaa_race_update_pace");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_posincat":
				$race->updateRacePosInCat();
				queue_flash_message("bhaa_race_posincat");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_posinstd":
				$race->updateRacePosInStd();
				queue_flash_message("bhaa_race_posinstd");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_postracestd":
				$race->updatePostRaceStd();
				queue_flash_message("bhaa_race_postracestd");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_league":
				$race->updateLeague();
				queue_flash_message("bhaa_race_league");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_all":
				$race->updateAll();
				queue_flash_message("bhaa_race_postracestd");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_team_results_delete":
				exit();
				break;
			case "bhaa_team_results_load":
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
	
	function bhaa_manage_race_posts_custom_column( $column, $post_id )
	{
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