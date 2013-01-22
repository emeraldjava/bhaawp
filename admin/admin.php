<?php
class BhaaAdmin
{
	var $raceResult;
	var $import;
	var $raceAdmin;
		
	function __construct()
	{
		require_once( ABSPATH . 'wp-admin/includes/template.php' );
				
		require_once (dirname (__FILE__) . '/race.admin.php');
		$this->raceAdmin = new RaceAdmin();
		
		require_once (dirname (__FILE__) . '/import.php');
		$this->import = new BhaaImport();
			
		//add_action('admin_init',array($this->raceAdmin,'init'));
		add_action( 'admin_menu', array(&$this,'bhaa_admin_plugin_menu') );
		add_action( 'admin_init', array(&$this,'register_bhaa_options') );
	}
	
	function register_bhaa_options()
	{
		register_setting('bhaa','bhaa_annual_event_id');
	}
	
	function bhaa_admin_plugin_menu()
	{
		add_options_page( 'BHAA Plugin Options', 'BHAA', 'manage_options', 'bhaa-options', array(&$this,'bhaa_plugin_options'));
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
		        <th scope="row">BHAA Annual Event ID</th>
		        <td><input type="text" name="bhaa_annual_event_id" value="<?php echo get_option('bhaa_annual_event_id'); ?>" /></td>
		        </tr>
		    </table>
		    <?php submit_button(); ?>
		</form>
		</div>
		<?php 		
	}
}
?>