<?php 
class Bhaa_Admin {

	protected static $instance = null;
	
	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	
	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
	
		/*
		 * TODO :
		*
		* - Decomment following lines if the admin class should only be available for super admins
		*/
		/* if( ! is_super_admin() ) {
		 return;
		} */
	
		$plugin = Bhaa::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	
		// Load admin style sheet and JavaScript.
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings'));
	
		// Add an action link pointing to the options page.
		$plugin_basename = ( dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/' . $this->plugin_slug . '.php' );
		//$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		//add_filter('plugin_action_links_'.$plugin_basename, array( $this, 'add_action_links' ) );
	
		/*
		 * Define custom functionality.
		*
		* Read more about actions and filters:
		* http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		*/
		//add_action( 'TODO', array( $this, 'action_method_name' ) );
		//add_filter( 'TODO', array( $this, 'filter_method_name' ) );
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		/* if( ! is_super_admin() ) {
		 return;
		} */
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), wp_flickr::VERSION );
		}
	}
	
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), wp_flickr::VERSION );
		}
	}
	
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		* TODO:
		*
		* - Change 'Page Title' to the title of your plugin admin page
		* - Change 'Menu Text' to the text for menu item for the plugin settings page
		* - Change 'manage_options' to the capability you see fit
		*   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		*/
/*		$this->plugin_screen_hook_suffix = add_options_page(
				__( 'Wordpress PHP Flickr', $this->plugin_slug ),
				__( 'Wp Php Flickr', $this->plugin_slug ),
				'manage_options',
				$this->plugin_slug,
				array( $this, 'display_plugin_admin_page' )
		);*/
		
		add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'main'));
		add_submenu_page('bhaa', 'BHAA', 'Members JSON', 'manage_options', 'bhaa_admin_members_json', array(&$this, 'bhaa_admin_members_json'));
		add_submenu_page('bhaa', 'BHAA', 'Day JSON', 'manage_options', 'bhaa_admin_day_json', array(&$this, 'bhaa_admin_day_json'));
		add_submenu_page('bhaa', 'BHAA', 'ALL HTML', 'manage_options', 'bhaa_admin_all_html', array(&$this, 'bhaa_admin_all_html'));
		add_submenu_page('bhaa', 'BHAA', 'Teams', 'manage_options', 'bhaa_admin_teams', array(&$this, 'bhaa_admin_teams'));
		add_submenu_page('bhaa' ,'BHAA','Standards','manage_options', 'bhaa_admin_standards' , array(&$this, 'bhaa_admin_standards'));
		// options panel
		add_options_page( 'BHAA Plugin Options', 'BHAA', 'manage_options', 'bhaa-options', array(&$this,'bhaa_plugin_options'));
		
	}
	
	public function register_settings() {
		//register_setting($this->plugin_slug,Wp_Php_Flickr::WP_FLICKR_USERNAME);
		//register_setting($this->plugin_slug,Wp_Php_Flickr::WP_FLICKR_USER_ID);
		//register_setting($this->plugin_slug,Wp_Php_Flickr::WP_FLICKR_API_KEY);
		//register_setting($this->plugin_slug,Wp_Php_Flickr::WP_FLICKR_SECRET);
	}
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		return array_merge(
				array(
						'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
				),
				$links
		);
	}
	
	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}
	
	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}
}
?>