<?php
// set_time_limit(300);
// ini_set('memory_limit', '16M');

// if(!class_exists('BhaaImport')){
// 	require_once( ABSPATH . 'wp-admin/includes/import.php' );
// }

// http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
class BhaaImport
{
	var $dbusername = 'wordpress';
	var $dbpassword = 'wordpress';
	var $dbname = 'wordpress';
	var $dbhost = 'localhost';
	
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
			$this->importEvents();
		elseif($_GET['action']=='deleteEvents')
			$this->deleteEvents();
		elseif($_GET['action']=='users')
			$this->importUsers();
		elseif($_GET['action']=='deleteUsers')
			$this->deleteUsers();
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
		echo '<b>Return to the <a href="admin.php?import=bhaa">BHAA Importer</a></b>';
		echo '<p>'.__('Steps may take a few minutes depending on the size of your database. Please be patient.').'</p>';
	}
	
	function footer()
	{
		echo '</div>';
	}
	
	function greet()
	{
		echo '<p>'.__('This importer allows you to import BHAA stuff.').'</p>';
		echo '<p>'.__('Hit the links below and pray:').'</p>';
		echo '<a href="admin.php?import=bhaa&action=events">Import BHAA Events</a> - <a href="admin.php?import=bhaa&action=deleteEvents">Delete Events</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=users">Import BHAA Users</a> - <a href="admin.php?import=bhaa&action=deleteUsers">Delete Users</a><br/>';
		echo '<br/>';
	}
	
	/**
	 * DELETE FROM `wp_posts` WHERE post_type=`event` and ID>10
	 *
	 * DELETE FROM `wp_em_events` WHERE event_id>10
	 *
	 */
	function importEvents()
	{
		$events = $this->getEvents();
		require_once( ABSPATH . 'wp-content/plugins/events-manager/classes/em-event.php' );
		require_once( ABSPATH . 'wp-content/plugins/events-manager/classes/em-location.php' );
		foreach($events as $event)
		{
			$count++;
	
			// default location 1
			$emLocation = new EM_Location();
			$emLocation->location_owner = 1;
			$emLocation->location_name = $event->tag.' '.$event->location;
			$emLocation->location_slug = $event->tag;
			$emLocation->location_address = $event->location;
			$emLocation->location_status = 'publish';
			$emLocation->location_town = 'Dublin';
			$emLocation->location_country = 'IE';
			$emLocation->post_title = $event->tag.' '.$event->location;
			$emLocation->post_name = $event->tag;
			$emLocation->save();
			echo '<p>emLocation '.$emLocation->post_id.' '.$emLocation->location_id.'</p>';
				
			$emEvent = new EM_Event();
			$emEvent->event_name = $event->name;
			$emEvent->event_slug = $event->tag;
			$emEvent->event_owner = 1;
			$emEvent->event_start_date = $event->date;
			$emEvent->event_end_date = $event->date;
			$emEvent->event_all_day = 0;
			$emEvent->event_start_time = '11:00:00';
			$emEvent->event_end_time = '11:00:00';
			$emEvent->post_content = $event->name.' - '.$event->tag;
			$emEvent->event_status = 'publish';
			$emEvent->event_date_created = date('Y-m-d H:i:s');
			$emEvent->location_id=$emLocation->location_id;
			$emEvent->save();
			echo '<p>emEvent '.$emEvent->post_id.' '.$emEvent->event_id.' '.$emLocation->location_id.'</p>';
			error_log('emEvent '.$emEvent->post_id.' '.$emEvent->event_id.' '.$emLocation->location_id);
			echo '<p>'.$count.' - '.$event->id.' '.$event->tag.' '.$event->name.'</p>';
		}
	}
	
	/**
	 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
	 * @param unknown_type $users
	 */
	function importUsers()
	{
		global $wpdb;
        $count = 0;
        echo '<p>'.__('Importing Users...').'<br /><br /></p>';

        $users = $this->getUsers();
        
        foreach($users as $user)
        {
        	$count++;
        	/**
        	 * http://codex.wordpress.org/Function_Reference/wp_insert_user
        	 * http://codex.wordpress.org/Function_Reference/wp_create_user
        	 */
//         	$ret_id = wp_insert_user(array(
//         	             'ID'            => $user->id,
//         		         'user_login'    => $user->email,
//         		         'user_nicename' => $user->email,
//         		         'user_email'    => $user->email,
//         		         'user_registered' => 'NOW()',
//         	             'display_name'  => $user->email));
        	//echo '<p>'.$count.' - '.$user->id.' '.$ret_id.'</p>';
        	//echo '<p>emEvent '.$emEvent->post_id.' '.$emEvent->event_id.' '.$emLocation->location_id.'</p>';
        	error_log(print_r($user));
        	
        }
	}

	function getEvents()
	{
		global $wpdb;
		//$gldb = new wpdb($this->dbusername,$this->dbpassword,$this->dbname,$this->dbhost);
		//set_magic_quotes_runtime(0);
		return $gldb->get_results('SELECT id,name,tag,date,location FROM event limit 25');//, ARRAY_A);
	}
	
	function deleteEvents()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_em_events WHERE event_id > %d",3)
		);
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_posts WHERE post_type = %s",'event')
		);
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_em_locations WHERE location_id > %d",3)
		);
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_posts WHERE post_type = %s",'location')
		);
	}
	
	function getUsers()
    {
    	global $wpdb;
    	return $wpdb->get_results($wpdb->prepare(
   			"SELECT id,firstname,surname,email,gender,email,dateofbirth,
    			company,companyname,team,newsletter,telephone,mobilephone,textmessage,
    			address1,address2,address3,status,insertdate,dateofrenewal FROM runner where ID = %d",7713));
	}
	
	function deleteUsers()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_users where ID != %d",1));
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_usermeta WHERE user_id != %d",1));
	}
}
//$import = new BhaaImport();
//register_importer('bhaaimport', 'BhaaImport', __('Import bhaa details'), array ($import, 'dispatch'));

?>