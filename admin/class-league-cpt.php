<?php
/**
 * Represents a BHAA league where events and their races are linked so that we can
 * aggregate the details of runner and team results.
 * 
 * This topic shows how to handle FORM submission via action hooks with a POST
 * http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
 * 
 * But want to use custom post-row-action URL which are based on URL with a GET
 * http://wordpress.stackexchange.com/questions/8481/custom-post-row-actions
 * 
 * The idea is to map the URL GET to a FORM POST via jquery
 * http://stackoverflow.com/questions/9748593/jquery-convert-get-url-to-post
 * 
 * http://wordpress.stackexchange.com/questions/99211/how-to-add-a-publish-link-to-the-quick-actions/99230#99230
 * http://wordpress.stackexchange.com/questions/82761/how-can-i-link-post-row-actions-with-a-custom-action-function
 * http://wordpress.stackexchange.com/questions/14973/row-actions-for-custom-post-types/14982#14982
 * 
 * @author oconnellp
 *
 */
class LeagueCpt {

	const BHAA_LEAGUE_RACES_TO_SCORE = 'races_to_score';
	const BHAA_LEAGUE_TYPE = 'bhaa_league_type';
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
		
	private function __construct() {
		add_action('init',array(&$this,'registerLeagueCPT'));

		//register_taxonomy_for_object_type('category', 'league');
		add_filter('post_row_actions', array(&$this,'bhaa_league_post_row_actions'), 0, 2);
		/* Filter the single_template with our custom function*/
		add_filter('single_template', array(&$this,'bhaa_single_league_template'));
		//add_shortcode('bhaa_league', array($this,'bhaa_league_shortcode'));
		
		// custom meta
		add_action( 'add_meta_boxes', array( &$this, 'bhaa_league_meta_data' ) );
		add_action( 'save_post', array( &$this, 'bhaa_league_save_meta_data' ) );
		
		// register the admin_action hook
		add_action( 'admin_menu', array( &$this,'bhaa_league_populate_metabox'));

		// handle FORM POST submit with admin_action_ hook
		// http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
		add_action('admin_action_bhaa_league_delete',array(&$this,'bhaa_league_delete'));
		add_action('admin_action_bhaa_league_populate',array(&$this,'bhaa_league_populate'));
	}	
	
	function bhaa_league_populate_metabox() {
		add_meta_box(
			'bhaa_league_populate',
			'League Actions',
			array(&$this, 'bhaa_league_populate_fields'),
			'league',
			'side',
			'high'
		);
	}
	
	function bhaa_league_populate_fields() {
		global $post;
		echo implode('<br/>', $this->get_admin_url_links($post));
	}
	
	/**
	 * Handle the URL GET call to edit.php
	 * http://wordpress.stackexchange.com/questions/82761/how-can-i-link-post-row-actions-with-a-custom-action-function?rq=1
	 */
	function bhaa_league_delete() {
		global $typenow;
		if( 'league' != $typenow )
			return;
		$leagueId = $_GET['post_id'];
		$leagueHandler = $this->getLeagueHandler($leagueId);
		$leagueHandler->deleteLeague();
		queue_flash_message("bhaa_league_delete");
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	
	/**
	 * Handle submit of the FORM
	 * http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
	 */
	function bhaa_league_populate() {
		error_log('bhaa_league_populate');
		$leagueId = $_GET['post_id'];
		$leagueHandler = $this->getLeagueHandler($leagueId);
		$leagueHandler->loadLeague();
		queue_flash_message("bhaa_league_populate");
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	
	/**
	 * Return a specific class for handling league actions.
	 * @param unknown $leagueid
	 * @return IndividualLeague|TeamLeague
	 */
	private function getLeagueHandler($leagueid) {
		$type = get_post_meta($leagueid,LeagueCpt::BHAA_LEAGUE_TYPE,true);
		if($type=='I')
			return new IndividualLeague($leagueid);
		else
			return new TeamLeague($leagueid);
	}
	
	/**
	 * http://wordpress.stackexchange.com/questions/17385/custom-post-type-templates-from-plugin-folder
	 */
	function bhaa_single_league_template($single) {
		global $wp_query, $post;
		/* Checks for single template by post type */
		if ($post->post_type == "league") {
			// check the type and redirect to a template
			$type = get_post_meta($post->ID,'bhaa_league_type',true);
			// check if this is a division sub-query
			if(isset($wp_query->query_vars['division'])) {
				$division = urldecode($wp_query->query_vars['division']);
				return BHAA_PLUGIN_DIR.'/includes/templates/single-league-division.php';
			} else {
				if($type=='T')
					return BHAA_PLUGIN_DIR.'/includes/templates/single-league-team.php';
				else 
					return BHAA_PLUGIN_DIR.'/includes/templates/single-league-individual.php';
			}
		}
		return $single;
	}
	
	/**
	 * should be moved to the league post
	 * @param unknown $atts
	 * @return string
	 */
	public function bhaa_league_shortcode($atts) {
	
		extract( shortcode_atts(
		array(
			'division' => 'A',
			'top' => '100'
		), $atts ) );
	
		$id = get_the_ID();
		$post = get_post( $id );
	
		$leagueSummary = new LeagueSummary($id);
		$summary = $leagueSummary->getDivisionSummary($atts['division'],$atts['top']);
		
		// division summary 
		if($atts['top']!=1000) {
			//$template = $this->mustache->loadTemplate('division-summary');
			return Bhaa_Mustache::get_instance()->loadTemplate('division-summary')->render(
				array(
					'division' => $atts['division'],
					'id'=>$id,
					'top'=> $atts['top'],
					'url'=> get_permalink( $id ),
					'linktype' => $leagueSummary->getLinkType(),
					'summary' => $summary
			));
		} else {
			
			//error_log('bhaa_league_shortcode detailed');
			if(strpos($atts['division'],'L'))
				$events = $leagueSummary->getLeagueRaces('W');
			else
				$events = $leagueSummary->getLeagueRaces('M');
			
			return Bhaa_Mustache::get_instance()->loadTemplate('division-detailed')->render(
				array(
					'division' => $atts['division'],
					'id'=>$id,
					'top'=> $atts['top'],
					'url'=> get_permalink( $id ),
					'summary' => $summary,
					'linktype' => $leagueSummary->getLinkType(),
					'events' => $events,
					'matchEventResult' => function($text, Mustache_LambdaHelper $helper) {
							$results = explode(',',$helper->render($text));
							//error_log($helper->render($text).' '.$results);
							$row = '';
							foreach($results as $result) {
								if($result==0)
									$row .= '<td>-</td>';
								else
									$row .= '<td>'.$result.'</td>';
							}
							return $row;				
						}
			));
		}
	}
	
	public function bhaa_league_meta_data() {
		add_meta_box(
			'bhaa_league_meta',
			__( 'League Details', 'bhaa_league_meta' ),
			array(&$this,'bhaa_league_meta_data_fields'),
			'league',
			'side',
			'high'
		);
	}
	
	function bhaa_league_meta_data_fields( $post ) {
		//wp_nonce_field( plugin_basename( __FILE__ ), 'bhaa_race_meta_data' );
	
		$races_to_score = get_post_custom_values(LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE, $post->ID);
		echo '<p>Races To Score <input type="text" name='.LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE.' value="'.$races_to_score[0].'" /></p>';
	
		// http://stackoverflow.com/questions/3507042/if-block-inside-echo-statement
		$type = get_post_custom_values(LeagueCpt::BHAA_LEAGUE_TYPE, $post->ID);
		echo '<p>Type <select name='.LeagueCpt::BHAA_LEAGUE_TYPE.'>';
		echo '<option value="I" '.(($type[0]=='I')?'selected="selected"':"").'>I</option>';
		echo '<option value="T" '.(($type[0]=='T')?'selected="selected"':"").'>T</option>';
		echo '</select></p>';
	}
	
	public function bhaa_league_save_meta_data($post) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
	
		if ( empty( $_POST ) )
			return;
	
		if ( !empty($_POST[LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE])) {
			update_post_meta( $post, LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE, $_POST[LeagueCpt::BHAA_LEAGUE_RACES_TO_SCORE] );
		}
		if ( !empty($_POST[LeagueCpt::BHAA_LEAGUE_TYPE])) {
			error_log("bhaa_league_save_meta_data ".$_POST[LeagueCpt::BHAA_LEAGUE_TYPE]);
			update_post_meta( $post, LeagueCpt::BHAA_LEAGUE_TYPE, $_POST[LeagueCpt::BHAA_LEAGUE_TYPE]);
		}
	}
	
	/**
	 * Add custom url link actions
	 * http://wordpress.stackexchange.com/questions/8481/custom-post-row-actions
	 * http://wordpress.org/support/topic/trying-to-add-custom-post_row_actions-for-custom-post-status
	 * http://wordpress.stackexchange.com/questions/14973/row-actions-for-custom-post-types
	 * http://wordpress.org/support/topic/replacement-for-post_row_actions
	 * http://www.ilovecolors.com.ar/saving-custom-fields-quick-bulk-edit-wordpress/
	 */
	function bhaa_league_post_row_actions($actions, $post) {
		if ($post->post_type =="league") {
			$actions = array_merge(
				$actions, $this->get_admin_url_links($post)
			);
		}
		return $actions;
	}
	
	private function get_admin_url_links($post){
		return array(
			'bhaa_league_delete' => $this->generate_admin_url_link('bhaa_league_delete',$post->ID,'Delete'),
			'bhaa_league_populate' => $this->generate_admin_url_link('bhaa_league_populate',$post->ID,'Populate')
		);
	}
	
	/**
	 * Use the admin.php page as the hook point
	 * http://shibashake.com/wordpress-theme/obscure-wordpress-errors-why-where-and-how
	 */
	private function generate_admin_url_link($action,$post_id,$name) {
		$nonce = wp_create_nonce( $action );
		//$link = admin_url( 'edit.php?post_type=league&action='.$action.'&post_id='.$post_id.'&_wpnonce='.$nonce );
		$link = admin_url('admin.php?action='.$action.'&post_type=league&post_id='.$post_id);//.'&_wpnonce='.$nonce );
		return '<a href='.$link.'>'.$name.'</a>';
	}
	
	//
	function registerLeagueCPT() {
		$leagueLabels = array(
			'name' => _x( 'Leagues', 'league' ),
			'singular_name' => _x( 'League', 'league' ),
			'add_new' => _x( 'Add New', 'league' ),
			'add_new_item' => _x( 'Add New League', 'league' ),
			'edit_item' => _x( 'Edit League', 'league' ),
			'new_item' => _x( 'New League', 'league' ),
			'view_item' => _x( 'View League', 'league' ),
			'search_items' => _x( 'Search Leagues', 'league' ),
			'not_found' => _x( 'No league found', 'league' ),
			'not_found_in_trash' => _x( 'No leagues found in Trash', 'league' ),
	        'parent_item_colon' => _x( 'Parent League:', 'league' ),
			'menu_name' => _x( 'BHAA Leagues', 'league' ),
			);
	
		$leagueArgs = array(
			'labels' => $leagueLabels,
			'hierarchical' => false,
			'description' => 'BHAA League Details',
			'supports' => array( 'title','editor','excerpt','thumbnail','comments'),
			'taxonomies' => array('category'),// post_tag
        	'public' => true,
        	'show_ui' => true,
        	'show_in_menu' => true,
        	'show_in_nav_menus' => true,
        	'publicly_queryable' => true,
	        'exclude_from_search' => false,
	        'has_archive' => true,
	        'query_var' => true,
	        'can_export' => true,
	        'rewrite' => true,
			'has_archive' => true,
	        'capability_type' => 'post'
	        );
		register_post_type('league', $leagueArgs );
		
		$labels = array(
			'name' => _x( 'Divisions', 'division' ),
			'singular_name' => _x( 'Division', 'division' ),
			'search_items' => _x( 'Search divisions', 'division' ),
			'popular_items' => _x( 'Popular divisions', 'division' ),
			'all_items' => _x( 'All divisions', 'division' ),
			'parent_item' => _x( 'Parent division', 'division' ),
			'parent_item_colon' => _x( 'Parent division:', 'division' ),
			'edit_item' => _x( 'Edit division', 'division' ),
			'update_item' => _x( 'Update division', 'division' ),
			'add_new_item' => _x( 'Add New division', 'division' ),
			'new_item_name' => _x( 'New division', 'division' ),
			'separate_items_with_commas' => _x( 'Separate divisions with commas', 'division' ),
			'add_or_remove_items' => _x( 'Add or remove divisions', 'division' ),
			'choose_from_most_used' => _x( 'Choose from most used divisions', 'division' ),
			'menu_name' => _x( 'Divisions', 'division' ),
		);
	}
}
?>