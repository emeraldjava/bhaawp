<?php 
class Bhaa {

	protected $plugin_slug = 'bhaawp';

	protected static $instance = null;
	
	var $race;
	var $individualResultTable;
	var $house;
	var $standardCalculator;
	var $registration;
	var $raceday;
	
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
	
		// Activate plugin when new blog is added
		//add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );
	
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	
		// hook add_query_vars function into query_vars
		add_filter('query_vars', array($this,'add_query_vars'));
		
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
		new LeagueCpt();
		new RaceCpt();
		new HouseCpt();
		new Events_Manager();
		
		// table views
		$this->individualResultTable = new RaceResult_List_Table();
		$this->registration = new Registration();
		$this->raceday = new Raceday();
		
		$this->standardCalculator = new StandardCalculator();
		add_shortcode('eventStandardTable', array($this->standardCalculator,'eventStandardTable'));
	}
	
	/**
	 * The runner and raceday pages will be server from the plugin templates directory.
	 */
	function bhaa_content($page_content) {
		global $post;//, $wpdb, $wp_query;
	
		//if( empty($post) )
		//return $page_content;
	
		//error_log("bhaa_content ".$post->ID);
		// realex 3143
		if( $post->ID == 3143) {
			$realex = new Realex();
			return $realex->process();
		}
		else if($post->ID==2025) {
			// runner page
			return $this->getRunnerPage();
		}
		else if($post->ID==2651){
			// raceday
			return $this->getRacedayPage();
		}
		
		/* 		if( in_array($post->ID, array(3091)) ) {//2025,2937,2940
		 error_log("bhaa_content ".$post->ID);
		ob_start();
		if( $post->ID == 3091) {//2025) {		// runner
		$this->bhaa_locate_template('leaguetable.php', true);// array('args'=>$args));
		} else if( $post->ID == 2025) {//2025) {		// runner
		$this->bhaa_locate_template('runner.php', true);// array('args'=>$args));
		} else if( $post->ID == 2937) {	// raceday
		$this->bhaa_locate_template('raceday.php', true);//, array('args'=>$args));
		}
		$page_content = ob_get_clean();
		} */
		else
			return $page_content;
	}
	
	private function getRunnerPage(){
		include_once('views/runner.php');
	}

	private function getRacedayPage(){
		include_once('views/raceday.php');
	}
	
	/**
	 * BHAA query vars
	 * http://wordpress.stackexchange.com/questions/46/what-are-all-the-available-parameters-for-query-posts
	 * http://codex.wordpress.org/Custom_Queries
	 * @return string
	 */
	function add_query_vars($aVars) {
		$aVars[] = "division";
		return $aVars;
	}
	
	
	function bhaa_locate_template( $template_name, $load=false, $args = array() ) {
		//First we check if there are overriding tempates in the child or parent theme
		$located = locate_template(array('plugins/bhaawp-master/'.$template_name));
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
	
	function getRaceday() {
		return $this->raceday;
	}
	
	function getRunnerManager() {
		return $this->runnerManager;
	}
	
	function getRaceResult() {
		return new RaceResult();
	}
	
	function getIndividualResultTable()	{
		return $this->individualResultTable;
	}
	
	public function getRaceTeamResultTable($race) {
		$teamResult = new TeamResult($race);
		return $teamResult->getRaceTeamResultTable();
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
		
		/**
		 * 		global $wpdb;
		$options = array();
		add_option( 'bhaa', $options, 'BHAA Options', 'yes' );
		//add_option( 'bhaa_widget', array(), 'BHAA Widget Options', 'yes' );

		// raceresult SQL
		$raceResult = new RaceResult();
		$this->run_install_or_upgrade($raceResult->getName(),$raceResult->getCreateSQL());

		$teamResultSql = "
				id int(11) NOT NULL AUTO_INCREMENT,
				team int(11) NOT NULL,
				league int(11) NOT NULL,
				race int(11) NOT NULL,
				standardtotal int(11),
				positiontotal int(11),
				class enum('A', 'B', 'C', 'D', 'W', 'O', 'OW'),
				leaguepoints int(11),
				status enum('ACTIVE','PENDING'),
				PRIMARY KEY (id)";
		$this->run_install_or_upgrade($wpdb->teamresult,$teamResultSql);
		$leagueSummaryModel = new LeagueSummary();
		$this->run_install_or_upgrade($leagueSummaryModel->getName(),$leagueSummaryModel->getCreateSQL());

		$ageCategory = new AgeCategory();
		$this->run_install_or_upgrade($ageCategory->getName(),$ageCategory->getCreateSQL());

		$importTableSql = "
				id int(11) NOT NULL AUTO_INCREMENT,
				tag varchar(15) NOT NULL,
				type varchar(15) NOT NULL,
				new int(11) NOT NULL,
				PRIMARY KEY (id)";

		$this->run_install_or_upgrade($wpdb->importTable,$importTableSql);
		// populate the table with the data
		$this->run_install_or_upgrade($wpdb->standardTable,$this->standardCalculator->standardTableSql);
		foreach ($this->standardCalculator->standards as $i => $standard) {
			$wpdb->insert( $wpdb->standardTable, (array)$standard );
		}
		
		function run_install_or_upgrade($table_name, $sql) {
		global $wpdb;
		// Table does not exist, we create it!
		// We use InnoDB and UTF-8 by default
		if ($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name)
		{
			$create = "CREATE TABLE ".$table_name." ( ".$sql." ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			error_log($create);
			// We use the dbDelta method given by WP!
			require_once ABSPATH.'wp-admin/includes/upgrade.php';
			dbDelta($create);
		}
	}
		 */
	}
	
	/**
	* Fired for each blog when the plugin is deactivated.
	*
	* @since    1.0.0
	*/
	private static function single_deactivate() {
		// TODO: Define deactivation functionality here
		/**
		 * 		global $wpdb;
		// tables
		$tables = array(
			//$wpdb->raceresult
		);
		// Delete each table one by one
		foreach ($tables as $table)	{
			$wpdb->query('DROP TABLE IF EXISTS '.$table.';');
		}
		delete_option( 'bhaa_widget' );
		delete_option( 'bhaa' );
		 */
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
		wp_enqueue_style( 
			$this->plugin_slug . '-plugin-styles', 
			plugins_url( 'assets/css/public.css', __FILE__ ), 
			array());//, self::VERSION );
		
		// http://stackoverflow.com/questions/8849684/wordpress-jquery-ui-css-files
		// css style
		//wp_enqueue_style(
		//'jquery-bhaa-style',
		//'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		
		/*wp_enqueue_style(
		 'bootstrap-css',
				plugins_url() . '/bhaawp-master/assets/css/bootstrap.min.css',
				false); */
		
		wp_enqueue_style(
			'bhaawp',
			plugins_url() . '/bhaawp-master/assets/css/bhaawp.css',
			false);
		wp_enqueue_style(
			'jquery-bhaa-style',
			plugins_url() . '/bhaawp-master/assets/css/jqueryui/jquery-ui-1.10.3.custom.min.css',
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
		wp_enqueue_script( 
			$this->plugin_slug . '-plugin-script', 
			plugins_url( 'assets/js/public.js', __FILE__ ), 
			array( 'jquery' ));//, self::VERSION );
		
		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		//wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );
		//wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		// http://wordpress.stackexchange.com/questions/56343/template-issues-getting-ajax-search-results/56349#56349
		wp_register_script(
		'bhaawp',
		plugins_url('assets/js/bhaawp.jquery.js',__FILE__),
		array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog'));
		
		wp_enqueue_script('bhaawp');
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
}
?>