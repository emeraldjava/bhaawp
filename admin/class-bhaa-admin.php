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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings'));
	
		// Add an action link pointing to the options page.
		//$plugin_basename = ( dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/' . $this->plugin_slug . '.php' );
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter('plugin_action_links_'.$plugin_basename, array( $this, 'add_action_links' ) );
	
		add_action('pre_user_query', array(&$this,'match_runners_who_have_raced'));
		
		RaceAdmin::get_instance();
		RunnerAdmin::get_instance();
		EventAdmin::get_instance();
		new WPFlashMessages();
		
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
			wp_enqueue_script( 
				$this->plugin_slug . '-admin-script', 
				plugins_url( 'assets/js/admin.js', __FILE__ ), 
				array( 'jquery' ), wp_flickr::VERSION );
		}
		
		/*
		wp_register_script(
			'bhaa_members',
			plugins_url( '/../admin/assets/js/bhaa_members.js'),
			array('jquery')
		);
		wp_enqueue_script('bhaa_members');
		
		wp_register_script(
			'bhaa-raceday',
			content_url().'/plugins/bhaawp/assets/js/bhaa-raceday.js');
		wp_enqueue_script('bhaa-raceday');
		*/
		
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
		//add_options_page( 'BHAA Plugin Options', 'BHAA', 'manage_options', 'bhaa-options', array(&$this,'bhaa_plugin_options'));
		
	}
	
	public function register_settings() {
		register_setting( 'bhaa', 'bhaa_annual_event_id');
		//register_setting( 'bhaa', 'bhaa_import_username' );
		//register_setting( 'bhaa', 'bhaa_import_password' );
		register_setting( 'bhaa', 'bhaa_bookings_enabled');
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
	
	/**
	 * BHAA Stuff
	 */
	
	function main() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		include_once( 'views/bhaa_admin_main.php' );
		//echo '<div class="wrap">';
		//echo '<p>BHAA Admin Page</p>';
	}

	function bhaa_admin_members_json() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		$file = BHAA_PLUGIN_DIR.'/admin/assets/js/bhaa_members.js';
		$content = 'var bhaa_members = ';
		if(isset($_POST['command']) && $_POST['command']=='bhaa_admin_members_json') {
			//echo 'command '.$_POST['command'];
			//$model = new BaseModel();
			// http://stackoverflow.com/questions/15494452/jqueryui-autocomplete-with-external-text-file-as-a-data-source
			//$content = '[{ label:"POC", value:"7713"}, { label:"AAA", url:"1"}]';
			// var bhaa_day_runners = 
			$user = new User();
			$content .= json_encode($user->getRegistrationRunnerDetails(array("M","I")));
			//error_log('file '.$file);
			if(file_exists($file)){
				file_put_contents($file, $content);
			}
		} else {
			$content = file_get_contents($file);
		}
		$_REQUEST['content']=$content;
		include_once( 'views/bhaa_admin_members_json.php' );
	}
	
	function bhaa_admin_day_json()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Day Members JSON</p>';
		
		$file = ABSPATH.'wp-content/bhaa_day_members.js';
		$content = 'var bhaa_day_members = ';
		if(isset($_POST['command']) && $_POST['command']=='bhaa_admin_day_json')
		{
			//echo 'command '.$_POST['command'];
			$model = new BaseModel();
			// http://stackoverflow.com/questions/15494452/jqueryui-autocomplete-with-external-text-file-as-a-data-source
			//$content = '[{ label:"POC", value:"7713"}, { label:"AAA", url:"1"}]';
			$content .= json_encode($model->getRegistrationRunnerDetails('D'));
			error_log('file '.$file);
			if(file_exists($file)){
				file_put_contents($file, $content);
			}
		}
		
		echo '<p><form action="'.get_permalink().'" id="bhaa_admin_day_json" method="post">
				<input type="hidden" name="command" value="bhaa_admin_day_json"/>
				<input type="Submit" value="Refresh Day Runners"/>
			</form></p>';
		echo '<hr/>';
		echo file_get_contents($file);
		echo '</div>';
	}
	
	function bhaa_admin_all_html() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA ALL Members HTML</p>';
		
		$file = ABSPATH.'wp-content/bhaa_all_members.html';
		$content = '<div><ul>';
		if(isset($_POST['command']) && $_POST['command']=='bhaa_admin_all_html'){
			$model = new BaseModel();
			$runners = $model->getRegistrationRunnerDetails("M,I,D");
			foreach($runners as $runner){
				$content .= sprintf("<li>%s %s ,ID:%d ,Status:%s, DOB:%s</li>",
					$runner->lastname,$runner->firstname,$runner->id,$runner->status,$runner->dob);
			}
			$content .= '</ul></div>';
			error_log('file '.$file);
			if(file_exists($file)){
				file_put_contents($file, $content);
			}
		}
		
		echo '<p><form action="'.get_permalink().'" id="bhaa_admin_all_html" method="post">
				<input type="hidden" name="command" value="bhaa_admin_all_html"/>
				<input type="Submit" value="Refresh All Runners Html"/>
			</form></p>';
		echo '<hr/>';
		echo file_get_contents($file);
		echo '</div>';
	}
	
	// http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
	function bhaa_admin_teams()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		echo '<div class="wrap">';
		echo '<h2>Lists the runner with a company but no correctly linked</h2>';
		
		if(isset($_POST['command']) && $_POST['command']=='link_runner') {
			Connections::get_instance()->updateRunnersHouse(Connections::HOUSE_TO_RUNNER,$_POST['house'],$_POST['runner']);
			echo 'Linked '.$_POST['runner'].' to '.$_POST['house'].' company</br>';
		}
				
		$SQL = 'select wp_users.id as id, wp_users.display_name as display_name, status.meta_value as status, dor.meta_value as dor,
			company.meta_value as company, house.post_title as house, r2c.p2p_from from wp_users
			left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = "bhaa_runner_company")
			join wp_posts house on (house.id=company.meta_value and house.post_type="house")
			left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = "house_to_runner")
			left join wp_usermeta status ON (status.user_id=wp_users.id AND status.meta_key = "bhaa_runner_status")
			left join wp_usermeta dor ON (dor.user_id=wp_users.id AND dor.meta_key = "bhaa_runner_dateofrenewal")
			where company.meta_value IS NOT NULL and r2c.p2p_from IS NULL and status.meta_value="M" and company.meta_value!=1';
		global $wpdb;
		$results = $wpdb->get_results($SQL,OBJECT);
		foreach($results as $row){
			$runner_url = sprintf('<a target=new r="%d" href="/runner/?id=%d"><b>%d</b></a>',
				$row->id,$row->id,$row->id
			);
			$company_url = sprintf('<a target=new href="/?post_type=house&p=%d"><b>%s</b></a>',$row->company,$row->house);
			
			$form = sprintf('<form action="'.get_permalink().'" id="link_runner" method="post"><input type="hidden" name="command" value="link_runner"/><input type="hidden" name="runner" value="%d"/><input type="hidden" name="house" value="%d"/><input type="Submit" value="Link %d to %s"/></form>',$row->id,$row->company,$row->id,$row->house);
			
			echo $runner_url.' '.$row->display_name.' '.$company_url.' :: '.$form.'</br>';
		};
		echo '</div>';
	}
		
	function bhaa_admin_standards()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		
		echo '<p>hi</p>';
		
		$members = array(
				'meta_key' => 'bhaa_runner_status',
				'meta_value' => 'M',
				'meta_compare' => '='
		);
		
		// http://wordpress.stackexchange.com/questions/76622/wp-user-query-to-exclude-users-with-no-posts
		$missingStandard = array(
				'meta_query' => array(
						'relation' => 'AND',
						array(
								'key' => 'bhaa_runner_status',
								'value' => 'M',
								'compare' => '='
						),
						array(
								'key' => 'bhaa_runner_standard',
								'compare' => 'NOT EXISTS'
						)
				),
				'orderby'=>'ID',
				'fields'=>'all',
				'query_id'=>'match_runners_who_have_raced'
		);
		
		$user_query = new WP_User_Query( $missingStandard );
		echo 'members :'.$user_query->get_total();
		
		if ( ! empty( $user_query->results ) ) {
			foreach ( $user_query->results as $user ) {
				//echo '<p>' .$user->ID.' - '.$user->display_name . '</p>';
				echo sprintf('<div>%d <a href="%s" target="new">%s</a></div>',
						$user->ID,
						add_query_arg(array('id'=>$user->ID),'/runner'),$user->display_name);
			}
		}	
		wp_reset_query();
	}
	
	function match_runners_who_have_raced( $query ) {
		if ( isset( $query->query_vars['query_id'] ) && 'match_runners_who_have_raced' == $query->query_vars['query_id'] ) {
			$query->query_from = $query->query_from . ' LEFT OUTER JOIN (
                SELECT runner, COUNT(race) as races
                FROM wp_bhaa_raceresult
				GROUP BY runner
            ) rr ON (wp_users.ID = rr.runner)';
			$query->query_where = $query->query_where . ' AND rr.races > 0 ';
		}
	}
}
?>