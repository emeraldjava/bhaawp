<?php
class BhaaAdmin
{	
	function __construct()
	{
		require_once( ABSPATH . 'wp-admin/includes/template.php' );
		$import = new BhaaImport();

		$raceAdmin = new RaceAdmin();
		$runnerAdmin = new RunnerAdmin();
		new WPFlashMessages();
		
		add_action('admin_menu',array(&$this,'bhaa_admin_plugin_menu'));
		add_action('admin_init',array(&$this,'register_bhaa_options'));
		
		add_action('pre_user_query', array(&$this,'match_runners_who_have_raced'));
		
		add_action('admin_action_bhaa_add_runner', array(&$this,'admin_action_bhaa_add_runner'));
	}
	
	function bhaa_admin_plugin_menu()
	{
		add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'main'));
		add_submenu_page('bhaa', 'BHAA', 'Members JSON', 'manage_options', 'bhaa_admin_members_json', array(&$this, 'bhaa_admin_members_json'));
		add_submenu_page('bhaa', 'BHAA', 'Day JSON', 'manage_options', 'bhaa_admin_day_json', array(&$this, 'bhaa_admin_day_json'));
		add_submenu_page('bhaa', 'BHAA', 'ALL HTML', 'manage_options', 'bhaa_admin_all_html', array(&$this, 'bhaa_admin_all_html'));
		add_submenu_page('bhaa', 'BHAA', 'Teams', 'manage_options', 'bhaa_admin_teams', array(&$this, 'bhaa_admin_teams'));
		add_submenu_page('bhaa' ,'BHAA','Standards','manage_options', 'bhaa_admin_standards' , array(&$this, 'bhaa_admin_standards'));
		// options panel
		add_options_page( 'BHAA Plugin Options', 'BHAA', 'manage_options', 'bhaa-options', array(&$this,'bhaa_plugin_options'));
	}
	
	function main()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Admin Page</p>';
	}

	function bhaa_admin_members_json()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Members JSON</p>';
		
		$file = ABSPATH.'wp-content/bhaa_members.js';
		$content = 'var bhaa_members = ';
		if(isset($_POST['command']) && $_POST['command']=='bhaa_admin_members_json')
		{
			echo 'command '.$_POST['command'];
			$model = new BaseModel();
			// http://stackoverflow.com/questions/15494452/jqueryui-autocomplete-with-external-text-file-as-a-data-source
			//$content = '[{ label:"POC", value:"7713"}, { label:"AAA", url:"1"}]';
			// var bhaa_day_runners = 
			$content .= json_encode($model->getRegistrationRunnerDetails(array('M','I')));
			error_log('file '.$file);
			if(file_exists($file)){
				file_put_contents($file, $content);
			}
		}
		
		echo '<p><form action="'.get_permalink().'" id="bhaa_admin_members_json" method="post">
				<input type="hidden" name="command" value="bhaa_admin_members_json"/>
				<input type="Submit" value="Refresh Members"/>
			</form></p>';
		echo '<hr/>';
		echo file_get_contents($file);
		echo '</div>';
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
			$model = new BaseModel();
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
		echo '<p>TODO List the runners not linked to teams';
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
	
	function register_bhaa_options()
	{
		register_setting( 'bhaa', 'bhaa_annual_event_id');
		register_setting( 'bhaa', 'bhaa_flickr_username' );
		register_setting( 'bhaa', 'bhaa_flickr_user_id' );
		register_setting( 'bhaa', 'bhaa_flickr_api_key' );
		register_setting( 'bhaa', 'bhaa_flickr_secret' );
		register_setting( 'bhaa', 'bhaa_import_username' );
		register_setting( 'bhaa', 'bhaa_import_password' );
		register_setting( 'bhaa', 'bhaa_enable_booking');
	}
			
	function bhaa_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
		<h2>BHAA Options Page</h2>
		<form method="post" action="options.php">
		    <?php settings_fields( 'bhaa' ); ?>
		    <?php do_settings_sections( 'bhaa' ); ?>
		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row">bhaa_annual_event_id</th>
		        <td><input type="text" name="bhaa_annual_event_id" value="<?php echo get_option('bhaa_annual_event_id'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_flickr_username</th>
		        <td><input type="text" name="bhaa_flickr_username" value="<?php echo get_option('bhaa_flickr_username'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_flickr_user_id</th>
		        <td><input type="text" name="bhaa_flickr_user_id" value="<?php echo get_option('bhaa_flickr_user_id'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_flickr_api_key</th>
		        <td><input type="text" name="bhaa_flickr_api_key" value="<?php echo get_option('bhaa_flickr_api_key'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_flickr_secret</th>
		        <td><input type="text" name="bhaa_flickr_secret" value="<?php echo get_option('bhaa_flickr_secret'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_import_username</th>
		        <td><input type="text" name="bhaa_import_username" value="<?php echo get_option('bhaa_import_username'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_import_password</th>
		        <td><input type="text" name="bhaa_import_password" value="<?php echo get_option('bhaa_import_password'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_enable_booking</th>
		        <td><input type="text" name="bhaa_enable_booking" value="<?php echo get_option('bhaa_enable_booking'); ?>" /></td>
		        </tr>
		    </table>
		    <?php submit_button(); ?>
		</form>
		</div>
		<?php 		
	}
}
?>