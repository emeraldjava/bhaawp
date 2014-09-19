<?php 
class Bhaa {

	protected $plugin_slug = 'bhaawp';

	protected static $instance = null;

	var $race;
	var $house;

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		require_once (dirname (__FILE__) . '/../bootstrap.php');
		//require_once( plugin_dir_path( __FILE__ ) . '/includes/class-flickr-shortcode.php' );

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'widgets_init', array($this,'bhaa_register_widgets'));

		// Activate plugin when new blog is added
		//add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// hook add_query_vars function into query_vars
		add_filter('query_vars', array($this,'bhaa_add_query_vars'));

		// filter the bhaa content pages
		add_filter('the_content', array($this,'bhaa_content'));

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		*/
		//add_shortcode('wp_flickr',array($flickr_shortcode,'wp_flickr_list_album'));
		//add_action( 'TODO', array( $this, 'action_method_name' ) );
		//add_filter( 'TODO', array( $this, 'filter_method_name' ) );


		// TODO remove these
		define('BHAAWP_PATH', plugin_dir_path(__FILE__));
		global $wpdb;
		$wpdb->raceresult 	= $wpdb->prefix.'bhaa_raceresult';
		$wpdb->teamresult 	= $wpdb->prefix.'bhaa_teamresult';
		$wpdb->importTable = $wpdb->prefix.'bhaa_import';
		$wpdb->standardTable = $wpdb->prefix.'bhaa_standard';

		Connections::get_instance();
		new HouseCpt();
		new Events_Manager();
		LeagueCpt::get_instance();

		// register the forms
		add_action('wp_forms_register',array(Raceday::get_instance(),'bhaa_register_forms'));
		add_action('wp_login',array($this,'bhaa_force_pretty_displaynames',10,2));
		add_filter('login_message',array($this,'bhaa_lost_password_message'));

		add_shortcode('pdf',array($this,'pdf_shortcode'));
		add_shortcode('bhaa_standard_table', array(StandardCalculator::get_instance(),'bhaa_standard_table'));
		Bhaa_Shortcode::get_instance()->registerShortCodes();

		add_action('admin_init',array($this,'bhaa_remove_subscriber_read'));
		add_action('wp_head',array($this,'bhaa_hide_admin_bar'));
		add_filter('login_redirect',array($this,'bhaa_redirect_subscriber_to_home'), 10, 3 );
		add_filter('wp_nav_menu_items',array($this,'bhaa_add_login_out_item_to_menu'), 50, 2 );
	}

	function bhaa_register_widgets() {
		register_widget('RunnerSearchWidget');
	}

	//http://wordpress.stackexchange.com/questions/93843/disable-wp-admin-console-for-subscribers/93869#93869
	function bhaa_remove_subscriber_read() {
		global $wp_roles;
		$wp_roles->remove_cap( 'subscriber', 'read' );
	}

	function bhaa_hide_admin_bar() {
		if (current_user_can('subscriber')) {
			add_filter('show_admin_bar','__return_false');
		}
	}

	function bhaa_redirect_subscriber_to_home( $redirect_to, $request, $user ) {
		if ( isset($user->roles) && is_array( $user->roles ) ) {
			if ( in_array( 'subscriber', $user->roles ) ) {
				return home_url( );
			}
		}
		return $redirect_to;
	}

	function bhaa_add_login_out_item_to_menu($nav, $args) {
		if( $args->theme_location == 'top_navigation' ){
			if(!is_user_logged_in())
				return $nav."<li class='menu-item'>".wp_loginout(get_site_url(), false)."</li>";
			else {
				global $current_user;
				return $nav."<li class='menu-item'>".wp_loginout(get_site_url(), false)."? Welcome to the BHAA website ".$current_user->first_name."</li>";
			}
		}
		else
			return $nav;
	}

	/**
	 * The runner and raceday pages will be server from the plugin templates directory.
	 */
	function bhaa_content($page_content) {
		global $post;
		//error_log($post->ID.' '.$post->post_type.' '.get_query_var('pagename'));
		// realex 3143
		if( $post->ID == 3143) {
			return Realex::get_instance()->process();
		}
		//else if($post->ID==2025) {
		// runner page
		//include_once('views/runner.php');
		//}
		else if($post->ID==3658){
			// runner-editresult
			include_once('views/runner-editresult.php');
		}
		else if(in_array($post->ID,array(2651,2653,2657,2869,2698,2655,2696,2698,2745,2847))) {
				
			if ( !current_user_can( 'edit_users' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			} else {

				if($post->ID==2651) {
					wp_register_script(
					'bhaa_members',
					plugins_url('/admin/assets/js/bhaa_members.js',dirname(__FILE__)),
					array('jquery'),
					null,
					false
					);
					wp_enqueue_script('bhaa_members');//,false,array(),"poc_version",false);

					wp_register_script(
					'bhaa-raceday',
					plugins_url('/admin/assets/js/bhaa-raceday.js',dirname(__FILE__))
					);
					wp_enqueue_script('bhaa-raceday');
				}

				$pagename = get_query_var('pagename');
				//error_log($post->ID.' '.$pagename);
				//error_log($page_content);
				return Raceday::get_instance()->handlePage($pagename);
			}
		}
		//else if($post->ID==2623)
		//include_once('views/sectors.php');
		// CPTS
		//else if($post->post_type=='house') {
		//error_log("house "+$post->post_type);
		//include_once('views/house.php');
		//}
		//else if($post->post_type=='race')
		//	return 'BHAA Race '.$post->ID.' '.$post->post_title;
		//else if($post->post_type=='league')
		//return 'BHAA League '.$post->ID.' '.$post->post_title;
		else
			return $page_content;
	}

	/**
	 * Register BHAA query vars
	 * http://stackoverflow.com/questions/4586835/how-to-pass-extra-variables-in-url-with-wordpress
	 * http://wordpress.stackexchange.com/questions/46/what-are-all-the-available-parameters-for-query-posts
	 * http://codex.wordpress.org/Custom_Queries
	 * @return string
	 */
	function bhaa_add_query_vars($qvars) {
		$qvars[] = "bhaaid";
		$qvars[] = "division";
		return $qvars;
	}


	function bhaa_locate_template( $template_name, $load=false, $args = array() ) {
		//First we check if there are overriding tempates in the child or parent theme
		$located = locate_template(array('plugins/bhaawp/'.$template_name));
		if( !$located ) {
			if ( file_exists(BHAA_PLUGIN_DIR.'/templates/'.$template_name) ) {

				$located = BHAA_PLUGIN_DIR.'/templates/'.$template_name;
			}
		}
		$located = apply_filters('bhaa_locate_template', $located, $template_name, $load, $args);
		if( $located && $load ) {
			if( is_array($args) )
				extract($args);
			include($located);
		}
		return $located;
	}

	function getRunnerManager() {
		return $this->runnerManager;
	}

	/**
	 * TODO Move to a Team result class
	 * @param unknown $house
	 * @return string
	 */
	public function getTeamResultsForHouse($house){
		$teamResult = new TeamResult($race);
		$results = $teamResult->getHouseResults($house);
		//var_dump($results,true);

		$options =  array('extension' => '.html');
		$mustache = new Mustache_Engine(
				array(
						'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates',$options),
						'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates/partials',$options)
				)
		);

		$template = $mustache->loadTemplate('team-results');
		return $template->render(
				array(
						'results' => $results
				));
	}

	/**
	 * move to a houses class
	 */
	function bhaawp_house_search() {
		error_log('bhaawp_house_search '.$_REQUEST['term']);
		$posts = get_posts( array(
				's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
				'post_type' => 'house'
		) );
		$suggestions=array();

		global $post;
		foreach ($posts as $post):
		setup_postdata($post);
		$suggestion = array();
		$suggestion['label'] = esc_html($post->post_title);
		$suggestion['link'] = get_permalink();
		$suggestions[]= $suggestion;
		endforeach;

		wp_reset_postdata();
		$response = json_encode(array('matches'=>$suggestions));
		//error_log('bhaawp_house_search '.$response);
		echo $response;
		die();
		//exit;
	}

	/**
	 * TODO spl_autoload_register
	 * http://stackoverflow.com/questions/11833034/non-destructive-spl-autoload-register
	 *
	 * add_action ( 'init' , 'class_loader' );

	 function class_loader () {
	 // register an autoloader function for template classes
	 spl_autoload_register ( 'template_autoloader' );
	 }

	 function template_autoloader ( $class ) {
	 if ( file_exists ( LG_FE_DIR . "/includes/chart_templates/class.{$class}.php" ) )
	 	include LG_FE_DIR . "/includes/chart_templates/class.{$class}.php";

	 }

	 */


	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}
				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}
				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}
		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {
		global $wpdb;
		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
		WHERE archived = '0' AND spam = '0'
		AND deleted = '0'";
		return $wpdb->get_col( $sql );
	}

	/**
		* Fired for each blog when the plugin is activated.
		*
		* @since    1.0.0
		*/
	private static function single_activate() {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . 'languages/' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		//wp_enqueue_style(
		//	$this->plugin_slug . '-plugin-styles',
		//	plugins_url( 'assets/css/public.css', __FILE__ ),
		//	array());//, self::VERSION );

		// http://stackoverflow.com/questions/8849684/wordpress-jquery-ui-css-files
		// css style
		//wp_enqueue_style(
		//'jquery-bhaa-style',
		//'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

		wp_enqueue_style(
		'bootstrap-css',
		plugins_url().'/bhaawp/public/assets/css/bootstrap.min.css',
		false);
		wp_enqueue_style(
		'bhaawp-css',
		plugins_url().'/bhaawp/public/assets/css/bhaawp.css',
		false);
		wp_enqueue_style(
		'tablesorter-css',
		plugins_url().'/bhaawp/public/assets/css/tablesorter/tablesorter.css',
		false);
		wp_enqueue_style(
		'jquery-bhaa-style',
		plugins_url().'/bhaawp/public/assets/css/jqueryui/jquery-ui-1.10.3.custom.min.css',
		false);
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 * http://stackoverflow.com/questions/5790820/using-jquery-ui-dialog-in-wordpress
	 * http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script(
		//	$this->plugin_slug . '-plugin-script',
		//	plugins_url( 'assets/js/public.js', __FILE__ ),
		//	array( 'jquery' ));//, self::VERSION );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		//wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );
		//wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_register_script(
		'tablesorter',
		plugins_url('assets/js/jquery.tablesorter.js',__FILE__),
		array('jquery'));
		wp_enqueue_script('tablesorter');

		// http://wordpress.stackexchange.com/questions/56343/template-issues-getting-ajax-search-results/56349#56349
		wp_register_script(
		'bhaawp',
		plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
		array('jquery',
		'jquery-ui-core',
		'jquery-ui-widget',
		'jquery-ui-position',
		'jquery-ui-sortable',
		'jquery-ui-datepicker',
		'jquery-ui-autocomplete',
		'jquery-ui-dialog'));
		wp_enqueue_script('bhaawp');
			


		/*
		 wp_register_script(
		 		'bhaa-members',
		 		plugins_url( '/../admin/assets/js/bhaa_members.js', __FILE__ ),
		 		array('jquery')
		 );
		wp_enqueue_script('bhaa-members');

		wp_register_script(
				'bhaa-raceday',
				plugins_url( '/../admin/assets/js/bhaa-raceday.js', __FILE__ )
		);
		wp_enqueue_script('bhaa-raceday');
		*/


		// 		wp_register_script(
		// 		'bootstrap-js',
		//		plugins_url('assets/js/bootstrap.min.js',__FILE__),
		//	array('jquery'));
		wp_enqueue_script('bootstrap-js');
		wp_localize_script(
		'bhaawp',
		'bhaaAjax',
		array('ajaxurl'=>admin_url('admin-ajax.php')));

		// register ajax methods
		//add_action('wp_ajax_nopriv_bhaawp_house_search',array($this,'bhaawp_house_search'));
		//add_action('wp_ajax_bhaawp_house_search',array($this,'bhaawp_house_search'));

		// TODO should be in the admin section
		add_action('wp_ajax_nopriv_bhaawp_runner_search',array($this->runner,'bhaa_runner_search'));
		add_action('wp_ajax_bhaawp_runner_search',array($this->runner,'bhaa_runner_search'));
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

	function bhaa_lost_password_message() {
		$action = $_REQUEST['action'];
		if( $action == 'lostpassword' ) {
			$message = '<p class="message"><b>Please enter your email address below</b><br/>- If there is an error it maybe the case that we do not have your email linked to your account, you should send an email to <a href="mailto:info@bhaa.ie?Subject=Email Reset">info@bhaa.ie</a> with your name and BHAA ID and we can fix this up.</p>';
			return $message;
		}
	}

	// http://stackoverflow.com/questions/9326315/wordpress-change-default-display-name-publicy-as-for-all-existing-users
	function bhaa_force_pretty_displaynames($user_login, $user) {
		$outcome = trim(get_user_meta($user->ID, 'first_name', true) . " " . get_user_meta($user->ID, 'last_name', true));
		if (!empty($outcome) && ($user->data->display_name!=$outcome)) {
			wp_update_user( array ('ID' => $user->ID, 'display_name' => $outcome));
		}
	}

	function pdf_shortcode( $atts ) {
		extract( shortcode_atts( array(
		'href' => ''
				), $atts ) );
				// http://stackoverflow.com/questions/1244788/embed-vs-object
				return '<object data="'.$href.'" width="95%" height="675" type="application/pdf">
						<embed src="'.$href.'" width="95%" height="675" type="application/pdf" />
								</object>';
	}


	function count_team_runners( $query ) {
		if ( isset( $query->query_vars['query_id'] ) && 'count_team_runners' == $query->query_vars['query_id'] ) {
			$query->query_from = $query->query_from . ' LEFT OUTER JOIN (
					SELECT COUNT(p2p_id) as runners
					FROM wp_p2p
					) p2p ON (wp_users.ID = p2p.p2p_from)';
			//$query->query_where = $query->query_where . ' AND rr.races > 0 ';
		}
	}
	//add_action('pre_user_query', array(&$this,'count_team_runners'));
}
?>