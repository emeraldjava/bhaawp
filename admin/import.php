<?php
// set_time_limit(200);
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

        require_once( ABSPATH . 'wp-includes/user.php' );
        foreach($users as $user)
        {

        	$count++;
        	/**
        	 * http://codex.wordpress.org/Function_Reference/wp_insert_user
        	 * http://codex.wordpress.org/Function_Reference/wp_create_user
        	 */
//         	id,
//         	firstname,
//         	surname,
//         	email,
//         	gender,
//         	email,
//         	dateofbirth,
//         	company,
//         	companyname,
//         	team,
//         	newsletter,
//         	telephone,
//         	mobilephone,
//         	textmessage,
//         	address1,
//         	address2,
//         	address3,
//         	status,
//         	insertdate,
//         	dateofrenewal

//        	wp_create_user( $username, $password, $email );

        	$name = strtolower($user->firstname.'.'.$user->surname);
        	$name = str_replace(' ', '', $name);
        	$name = str_replace("'", '', $name);
        	$password = wp_hash_password($user->id);
        	
        	// DB insert user
        	$this->insertUser($user->id,$name,$password,$user->email);

        	// http://codex.wordpress.org/Function_Reference/wp_insert_user
        	// INSERT INTO `wp_users`(`ID`) VALUES (7713);
        	$id = wp_insert_user(array(
        	        'ID'            => $user->id,
        		    'user_login'    => $name,// strtolower(mysql_real_escape_string($user->firstname.'.'.$user->surname)),// $user->email,
        			//'user_pass' => wp_hash_password($user->id),
        		    //'user_nicename' => $user->email,
        			// ''user_url
        		    'user_email'    => $user->email,
        			'nickname' => $user->firstname.' '.$user->surname,
        			'display_name'=> $user->firstname.' '.$user->surname,
        			'first_name' => $user->firstname,
        			'last_name'=> $user->surname
        	//	    'user_registered' => 'NOW()'
				));
								
			if ( is_wp_error($id) )
			{
				error_log($id->get_error_message());
				echo $id->get_error_message();
        	}
			update_user_meta( $id, 'rich_editing', 'false');

			if(isset($user->dateofbirth))
				update_user_meta( $id, 'bhaa_runner_dateofbirth', $user->dateofbirth);
			if(isset($user->company))
				update_user_meta( $id, 'bhaa_runner_company', $user->company);
			if(isset($user->companyname))
				update_user_meta( $id, 'bhaa_runner_companyname', $user->companyname);			
			//         	team,
			if(isset($user->newsletter))
				update_user_meta( $id, 'bhaa_runner_newsletter', $user->newsletter);
			//         	telephone,
			if(isset($user->telephone))
				update_user_meta( $id, 'bhaa_runner_telephone', $user->telephone);
			//         	mobilephone,
			if(isset($user->mobilephone))
				update_user_meta( $id, 'bhaa_runner_mobilephone', $user->mobilephone);
			//         	textmessage,
			if(isset($user->textmessage))
				update_user_meta( $id, 'bhaa_runner_textmessage', $user->textmessage);
			//         	address1,
			if(isset($user->address1))
				update_user_meta( $id, 'bhaa_runner_address1', $user->address1);
			//         	address2,
			if(isset($user->address2))
				update_user_meta( $id, 'bhaa_runner_address2', $user->address2);
			//         	address3,
			if(isset($user->address3))
				update_user_meta( $id, 'bhaa_runner_address3', $user->address3);
			//         	status,
			if(isset($user->status))
				update_user_meta( $id, 'bhaa_runner_status', $user->status);
			//         	insertdate,
			if(isset($user->insertdate))
				update_user_meta( $id, 'bhaa_runner_insertdate', $user->insertdate);
			//         	dateofrenewal
			if(isset($user->dateofrenewal))
				update_user_meta( $id, 'bhaa_runner_dateofrenewal', $user->dateofrenewal);
			
			echo '<p>'.$id.'</p>';
			error_log($id);
			
			$u = new WP_User( $id );
			$u->add_role( 'subscriber' );
        }
	}

	function getEvents()
	{
		global $wpdb;
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
   			'SELECT 
    			id,
    			firstname,
    			surname,
    			email,
    			gender,
    			email,
    			dateofbirth,
    			company,
    			companyname,
    			team,
    			newsletter,
    			telephone,
    			mobilephone,
    			textmessage,
    			address1,
    			address2,
    			address3,
    			status,
    			insertdate,
    			dateofrenewal
    		FROM runner where 
   			id IN (%d, %d, %d, %d, %d, %d, %d, %d, %d)',
    			7713, 1050, 6349, 5143, 7905, 5738, 7396, 10137, 10143));
    		// status != 'D'"));
    		// ID IN (%d,%d)",7713,1050));
	}

	/**
	 * INSERT INTO `wp_users`(`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES 
	 * ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10])
	 * @param unknown_type $id
	 * @param unknown_type $name
	 */
	function insertUser($id,$name,$password,$email)
	{
		global $wpdb;
		$sql;
		if(isset($email))
		{
			$sql = $wpdb->prepare(
				"INSERT INTO wp_users(
				ID,
				user_login,
				user_pass,
				user_nicename,
				user_email,
				user_status,
				display_name)
				VALUES (%d,%s,%s,%s,%s,%d,%s)",
				$id,$name,$password,$name,$email,0,$name);
		}	
		else
		{
			$sql = $wpdb->prepare(
				"INSERT INTO wp_users(
				ID,
				user_login,
				user_pass,
				user_nicename,
				user_status,
				display_name)
				VALUES (%d,%s,%s,%s,%s,%s)",
				$id,$name,$password,$name,0,$name);
		}
		$wpdb->query($sql);
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
?>