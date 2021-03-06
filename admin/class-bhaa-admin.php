<?php
class Bhaa_Admin {

	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a	settings page and menu.
	 */
	private function __construct() {
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
		//error_log("admin "+plugin_dir_path( __DIR__ )+" "+$plugin_basename);
		add_filter('plugin_action_links_'.$plugin_basename, array( $this, 'add_action_links' ) );

		add_action('admin_action_bhaa_send_email',array($this,'bhaa_send_email'));
		add_action('admin_action_bhaa_admin_send_text',array($this,'bhaa_admin_send_text'));

		RaceMaster::get_instance();
		Runner_Manager::get_instance();
		RunnerAdmin::get_instance();
		Raceday::get_instance()->registerAdminActions();
		EventAdmin::get_instance();
		new WPFlashMessages();
	}

	function bhaa_send_email() {

		$time = date("Y-m-d H:i:s",time());
		error_log('bhaa_send_email() start '.$time);

		$page = get_page_by_title('email_template_page');
		//$post = get_post($id);
		$content = apply_filters('the_content', $page->post_content);
		//error_log($content);

		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		$headers .= 'From: paul.oconnell@aegon.ie' . "\r\n";

		$style = "<link rel='stylesheet' id='avia-grid-css'  href='http://localhost/wp-content/themes/enfold/css/grid.css?ver=1' type='text/css' media='screen' />
		<link rel='stylesheet' id='avia-base-css'  href='http://localhost/wp-content/themes/enfold/css/base.css?ver=1' type='text/css' media='screen' />
		<link rel='stylesheet' id='avia-layout-css'  href='http://localhost/wp-content/themes/enfold/css/layout.css?ver=1' type='text/css' media='screen' />";

		$top = '<html><head>'.$style.'</head><body id="top" class="page"><div class="container">';

		$end = '</div></div></body></html>';

		wp_mail('email','Subject top content end '.$time,$top.$content.$end,$headers);
		error_log('bhaa_send_email() done');
		queue_flash_message("bhaa_send_email");
		wp_redirect(wp_get_referer());
		exit();
	}

	/**
	 * Return an instance of this class.
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
	 */
	public function add_plugin_admin_menu() {
		add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'bhaa_admin_main'));
//		add_submenu_page(null, 'BHAA', 'Registrar',
//			'manage_options','bhaa_admin_export_csv',
//			array(&$this, 'bhaa_admin_export_csv'));

		add_submenu_page('bhaa', 'BHAA', 'RaceMaster', 'manage_options', 'bhaa_admin_racemaster', array(&$this, 'bhaa_admin_racemaster'));

		add_submenu_page('bhaa', 'BHAA', 'Members JSON', 'manage_options', 'bhaa_admin_members_json', array(&$this, 'bhaa_admin_members_json'));


		//add_submenu_page('bhaa', 'BHAA', 'Day JSON', 'manage_options', 'bhaa_admin_day_json', array(&$this, 'bhaa_admin_day_json'));
		//add_submenu_page('bhaa', 'BHAA', 'ALL HTML', 'manage_options', 'bhaa_admin_all_html', array(&$this, 'bhaa_admin_all_html'));

		$registrarAdmin = new RegistrarAdmin();
		add_submenu_page('bhaa', 'BHAA', 'Registrar',
			'manage_options','bhaa_admin_registrar',
			array($registrarAdmin, 'bhaa_admin_registrar_page'));
		// use 'null' to register a hidden page
		add_submenu_page(null, 'BHAA', 'Registrar',
			'manage_options','bhaa-admin-registrar-monthly',
			array($registrarAdmin, 'bhaa_admin_registrar_monthly_page'));
		add_submenu_page(null, 'BHAA', 'Registrar',
			'manage_options','bhaa-admin-registrar-deactivate',
			array($registrarAdmin, 'bhaa_admin_registrar_deactivate_page'));

		add_submenu_page('bhaa', 'BHAA', 'Teams', 'manage_options','bhaa_admin_teams', array(&$this, 'bhaa_admin_teams'));

		$standardAdmin = new StandardAdmin();
		add_submenu_page('bhaa' ,'BHAA', 'Standards','manage_options','bhaa_admin_standards',array($standardAdmin,'bhaa_admin_standards'));
		add_submenu_page( null  ,'BHAA', 'Standards','manage_options','bhaa_admin_standard_list_members',array($standardAdmin,'bhaa_admin_standard_list_members'));
		add_submenu_page('bhaa' ,'BHAA', 'No Standard','manage_options','bhaa_admin_no_standard',array($standardAdmin,'bhaa_admin_no_standard'));

		//add_submenu_page('bhaa' ,'BHAA', 'Text','manage_options','bhaa_admin_text',array(&$this,'bhaa_admin_text'));
		//add_submenu_page('bhaa' ,'BHAA', 'Racetec','manage_options','bhaa_admin_racetec',array(&$this,'bhaa_admin_racetec'));
	}

	public function register_settings() {
		register_setting( 'bhaa', 'bhaa_registration_token');
		register_setting( 'bhaa', 'bhaa_bookings_enabled');
		//register_setting( 'bhaa', 'bhaa_bookings_enabled');
	}

	/**
	 * Add settings action link to the plugins page.
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

	function bhaa_admin_main() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$model = new RunnerModel();
		$membershipStatus = $model->getMembershipStatus();

		$idRunners = RunnerAdmin::get_instance()->getRunnersWithIdOver30000();
		$nextRunnerId = RunnerAdmin::get_instance()->getNextRunnerId();

		include_once( 'views/bhaa_admin_main.php' );
	}

	public function bhaa_admin_racemaster() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		$model = new RunnerModel();
		$membershipStatus = $model->getMembershipStatus();
		include_once( 'views/bhaa_admin_racemaster.php' );
	}

	function bhaa_admin_members_json() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$content = 'var bhaa_members = ';
		$file = BHAA_PLUGIN_DIR.'/public/assets/js/'.get_option('bhaa_members_file_date');
		if(isset($_POST['command']) && $_POST['command']=='bhaa_admin_members_json') {
			error_log('reload json content');
			unlink($file);

			$user = new RunnerModel();
			$content .= json_encode($user->getRegistrationRunnerDetails(array('M','I')));

			$date = date("Y-m-d");
			$filename = 'bhaa_members_'.$date.".js";
			update_option('bhaa_members_file_date',$filename);

			file_put_contents($file, $content);
            error_log("write to file ".$file,0);
		}

		$file = BHAA_PLUGIN_DIR.'/public/assets/js/'.get_option('bhaa_members_file_date');
		$content = file_get_contents($file);
		error_log("loading json context ".$file);
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
			$model = new RunnerModel();
			// http://stackoverflow.com/questions/15494452/jqueryui-autocomplete-with-external-text-file-as-a-data-source
			//$content = '[{ label:"POC", value:"7713"}, { label:"AAA", url:"1"}]';
			$content .= json_encode($model->getRegistrationRunnerDetails(array('D')));
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
			$model = new RunnerModel();
			$runners = $model->getRegistrationRunnerDetails(array('M','I','D'));
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

	/**
	 * Display the text submission form
	 */
	function bhaa_admin_text() {
		include_once('views/bhaa_admin_text_form.php');
	}

	/**
	 * http://jaskokoyn.com/2013/03/26/wordpress-admin-forms/
	 */
	function bhaa_admin_send_text() {
		$texter = new O2Texter();
		// 0863799240	BHAA10	Bhaa
		$loginResult = $texter->login('0863799240','Bhaa');
		error_log('$loginResult='.$loginResult);

		$gotoResult = $texter->goto_text_page();
		error_log('$gotoResult='.$gotoResult);

		if($loginResult==1) {
			$sendMessageResult = $texter->send_message('0863701860','bhaa_admin_send_text');
			error_log('$sendMessageResult='.$sendMessageResult);
		}

		$texter->delCookie();
		wp_redirect(admin_url('admin.php?page=bhaa_admin_text&result='.$sendMessageResult));
		exit;
	}
}
?>
