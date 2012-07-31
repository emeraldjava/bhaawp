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
		require_once( ABSPATH . 'wp-admin/includes/import.php' );
		register_importer('bhaa', 'BHAA', __('BHAA Importer'), array (&$this,'import'));
	}
	
	/**
	 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
	 * http://wordpress.org/support/topic/converting-geeklog-to-wordpress?replies=7
	 */
	public function import()
	{
		if (empty ($_GET['action']))
			$action = "";
		else
			$step = (int) $_GET['step'];
	
		$this->header();
		if(empty ($_GET['action']))
			$this->greet();
		elseif($_GET['action']=='events')
		$this->importA();
		elseif($_GET['action']=='users')
		$this->importA();
		else
			$this->greet();
		$this->footer();
	}
	
	function importA()
	{
		echo '<p>Action '.$_GET['action'].' was called</p>';
	}
	
	function header()
	{
		echo '<div class="wrap">';
		echo '<h2>'.__('Import BHAA').'</h2>';
		echo '<p>'.__('Steps may take a few minutes depending on the size of your database. Please be patient.').'</p>';
	}
	
	function footer()
	{
		echo '<br/><br/><b>Return to the <a href="admin.php?import=bhaa">BHAA Importer</a></b>';
		echo '</div>';
	}
	
	function greet()
	{
		echo '<p>'.__('This importer allows you to import BHAA stuff.').'</p>';
		echo '<p>'.__('Hit the links below and pray:').'</p>';
		echo '<a href="admin.php?import=bhaa&action=events">Import BHAA Events</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=users">Import BHAA Users</a><br/>';
		//		echo '<form action="admin.php?import=geeklog&amp;step=1" method="post">';
		//	$this->db_form();
		//echo '<input type="submit" name="submit" value="'.__('Import Categories').'" />';
		//echo '</form>';
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