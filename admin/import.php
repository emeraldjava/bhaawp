<?php
// set_time_limit(300);
// ini_set('memory_limit', '16M');

// if(!class_exists('BhaaImport')){
// 	require_once( ABSPATH . 'wp-admin/includes/import.php' );
// }

// http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
class BhaaImport
{
	function BhaaImport()
	{
		$this->__construct();
	}
	
	function __construct()
	{
		//require_once( ABSPATH . 'wp-admin/includes/import.php' );
		//$this->dispatch();
	}
	
	public function dispatch()
	{
// 		if ( !current_user_can( 'manage_options' ) )  {
// 			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
// 		}
		
		echo '<div class="wrap">';
        echo '<h2>'.__('Import GeekLog').'</h2>';
        echo '<p>BHAA</p>';
        echo '<p>'.__('Steps may take a few minutes depending on the size of your database. Please be patient.').'</p>';

        $users = $this->getrunners();
        $this->runners2wp($users);
        echo '</div>';
	}
	
	/**
	 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
	 * @param unknown_type $users
	 */
	function runners2wp($users='')
	{
		global $wpdb;
        $count = 0;
        $glid2wpid = array();
        echo '<p>'.__('Importing Users...').'<br /><br /></p>';
        
        foreach($users as $user)
        {
        	$count++;
        	//extract($user);
        	
//         	$USERNAME = $WPDB->ESCAPE($USERNAME);
//         	$USERNAME = $WPDB->ESCAPE($USERNAME);
//         	$USERNAME = $WPDB->ESCAPE($USERNAME);
        	
        	$ret_id = wp_insert_user(array(
        	             'ID'            => $user->id,
        		         'user_login'    => $user->email,
        		         'user_nicename' => $user->email,
        		         'user_email'    => $user->email,
        		         'user_registered' => 'NOW()',
        	             'display_name'  => $user->email));
        	echo '<p>'.$count.' - '.$user->id.' '.$ret_id.'</p>';
        }
	}
	
	function getrunners()
    {
    	global $wpdb;
        $gldb = new wpdb('wordpress','wordpress','wordpress','localhost');
        set_magic_quotes_runtime(0);
        $prefix = get_option('tpre');
		
		// Get Users
		return $gldb->get_results('SELECT id,firstname,surname,email,gender,email FROM runner where email IS NOT NULL and email !="" limit 5');//, ARRAY_A);
	}
}
//$import = new BhaaImport();
//register_importer('bhaaimport', 'BhaaImport', __('Import bhaa details'), array ($import, 'dispatch'));

?>