<?php
class BhaaAdmin
{
	var $raceResult;
	var $import;
	var $raceAdmin;
	private $runnerAdmin;
		
	function __construct()
	{
		require_once( ABSPATH . 'wp-admin/includes/template.php' );
		$this->import = new BhaaImport();
			
		$runnerAdmin = new RunnerAdmin();
		add_action('admin_menu', array(&$this,'bhaa_admin_plugin_menu') );
		add_action('admin_init', array(&$this,'register_bhaa_options') );
		
		add_action('admin_action_bhaa_add_runner', array(&$this,'admin_action_bhaa_add_runner'));
	}
	
	function bhaa_admin_plugin_menu()
	{
		add_menu_page('BHAA Admin Menu Title', 'BHAA', 'manage_options', 'bhaa', array(&$this, 'main'));
		add_submenu_page('bhaa', 'BHAA', 'Add Runner', 'manage_options', 'bhaa_add_runner', array(&$this, 'bhaa_add_runner_form'));
		add_submenu_page('bhaa' ,'BHAA','Enter Race','manage_options', 'bhaa_enter_race' , array(&$this, 'bhaa_enter_race'));
		add_submenu_page('bhaa' ,'BHAA','List Entry','manage_options', 'bhaa_list_entry' , array(&$this, 'bhaa_list_entry'));
		add_submenu_page('bhaa' ,'BHAA','Export','manage_options', 'bhaa_export_entry' , array(&$this, 'bhaa_export_entry'));
		
		// options panel
		add_options_page( 'BHAA Plugin Options', 'BHAA', 'manage_options', 'bhaa-options', array(&$this,'bhaa_plugin_options'));
	}
	
	function main()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>Main BHAA Registration Page.</p>';
		echo '</div>';
	}
	
	// http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
	function bhaa_add_runner_form()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Add Runner</p>';
		echo '<form action="'.admin_url( 'admin.php' ).'" method="POST">'.
			'<input type="hidden" name="action" value="bhaa_add_runner"/>'.
			'<input type="text" name="firstname"/><br/>'.
			'<input type="text" name="second"/><br/>'.
			'<input type="text" name="email"/><br/>'.
			'<input type="text" name="dateofbirth"/><br/>'.
			'<input type="text" name="gender"/><br/>'.
			'<input type="text" name="company"/><br/>'.
			'<input type="submit" value="Add Runner"/><br/>'.
			'</form>';
		echo '</div>';
	}
	
	function admin_action_bhaa_add_runner()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		// Do your stuff here
		error_log('admin_action_bhaa_add_runner '.$_POST['firstname'].' '.$_POST['second']);
		
		$runner = new Runner();
		$id = $runner->createNewUser($_POST['firstname'], $_POST['surname'], $_POST['email']);
		//wp_create_user($username, $password)
		
		echo '<div class="wrap">';
		echo '<p>New BHAA Runner Added : '.$id.'</p>';
		echo '</div>';
//		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
		
	function bhaa_enter_race()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Enter Race</p>';
		echo '<form action="'.admin_url( 'admin.php' ).'" method="POST">'.
				'<input type="hidden" name="action" value="bhaa_enter_race"/>'.
				'Runner: <input type="text" id="runner_search"/><br/>'.
				'Event: <input type="text" id="event"/><br/>'.
				'<input type="submit" value="Enter Runner"/><br/>'.
				'</form>';
		echo '</div>';
	}
	
	function bhaa_list_entry()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA List Entry</p>';
		echo '</div>';
	}
	
	function bhaa_export_entry()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Export Entry</p>';
		echo '</div>';
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
		        <th scope="row">bhaa_flickr_username</th>
		        <td><input type="text" name="bhaa_flickr_username" value="<?php echo get_option('bhaa_flickr_username'); ?>" /></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">bhaa_flickr_username</th>
		        <td><input type="text" name="bhaa_flickr_username" value="<?php echo get_option('bhaa_flickr_username'); ?>" /></td>
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