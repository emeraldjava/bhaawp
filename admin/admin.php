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
		
		add_action('admin_menu', array(&$this,'bhaa_admin_plugin_menu') );
		add_action('admin_init', array(&$this,'register_bhaa_options') );
		
		add_action('admin_action_bhaa_add_runner', array(&$this,'admin_action_bhaa_add_runner'));
	}
	
	function bhaa_admin_plugin_menu()
	{
		add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'main'));
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
		if(isset($_POST['command']))
		{
			echo 'command '.$_POST['command'];
		
			
		}
		
		echo '<p><form action="'.get_permalink().'" method="post">
				<input type="hidden" name="command" value="refresh"/>
				<input type="Submit" value="Refresh Runners"/>
			</form> BHAA Admin Page</p>';
		
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
		echo '<p>TODO List the runners with out a standard';
	}
	
	function register_bhaa_options()
	{
		register_setting('bhaa','bhaa_annual_event_id');
		register_setting( 'bhaa', 'bhaa_flickr_username' );
		register_setting( 'bhaa', 'bhaa_flickr_user_id' );
		register_setting( 'bhaa', 'bhaa_flickr_api_key' );
		register_setting( 'bhaa', 'bhaa_flickr_secret' );
		register_setting( 'bhaa', 'bhaa_import_username' );
		register_setting( 'bhaa', 'bhaa_import_password' );	
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
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_import_username</th>
		        <td><input type="text" name="bhaa_import_username" value="<?php echo get_option('bhaa_import_username'); ?>" /></td>
		        </tr>
		   		</tr>
		        <tr valign="top">
		        <th scope="row">bhaa_import_password</th>
		        <td><input type="text" name="bhaa_import_password" value="<?php echo get_option('bhaa_import_password'); ?>" /></td>
		        </tr>
		    </table>
		    <?php submit_button(); ?>
		</form>
		</div>
		<?php 		
	}
}
?>