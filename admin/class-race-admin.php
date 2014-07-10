<?php
/**
 * Race Admin Stuff
 * @author oconnellp
 * 
 * http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
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
		//add_action('init',array($this,'bhaa_race_actions'),11);

	}
	

	
	/**
	 * Filters for specific cpt actions.
	 */
	function bhaa_race_actions() {
		
		//error_log("GET  :".print_r($_GET,true));
		//error_log("POST :".print_r($_POST,true));
		// http://stackoverflow.com/questions/8463126/how-to-get-post-id-in-wordpress-admin
		$action = $_GET['action'];
		
		
		if(isset($action) && (substr($action, 0, 4) === 'bhaa')){
			//	global $post;
			//error_log(print_r($post,true));	
			//$post_id = $_GET['post_id'];
			error_log($post->ID);
			$raceResult = new RaceResult($post->ID);
		
		switch ($action) {
				
			case "bhaa_race_delete_results":
				$raceResult->deleteRaceResults();
				queue_flash_message("bhaa_race_delete_results");
				wp_redirect(wp_get_referer());
				exit();
				break;
			case "bhaa_race_load_results":
				$post = get_post($post_id);
				$resultText = $post->post_content;
				error_log('bhaa_race_load_results('.strlen($resultText).')');
				$results = explode("\n",$resultText);
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
				error_log('call bhaa-raceday-export admin');
				Raceday::get_instance()->export();
				exit();
				break;
			case 'bhaa_add_result':
				$raceResult->addDefaultResult();
				queue_flash_message("bhaa_add_result");
				wp_redirect(wp_get_referer());
				exit();
				break;
			}	
		}
	}
}
?>