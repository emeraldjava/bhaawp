<?php 
class Bhaa {

	protected $plugin_slug = 'bhaawp';

	protected static $instance = null;
	
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
		
		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		*/
		//add_shortcode('wp_flickr',array($flickr_shortcode,'wp_flickr_list_album'));
		//add_action( 'TODO', array( $this, 'action_method_name' ) );
		//add_filter( 'TODO', array( $this, 'filter_method_name' ) );
	}
	

	/**
	 * BHAA query vars
	 * http://wordpress.stackexchange.com/questions/46/what-are-all-the-available-parameters-for-query-posts
	 * @return string
	 */
	function add_query_vars($aVars) {
		$aVars[] = "division";
		return $aVars;
	}
	
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
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
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