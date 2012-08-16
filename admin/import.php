<?php
set_time_limit(300);
/**
 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
 * https://github.com/scribu/wp-posts-to-posts/wiki/Creating-connections-programatically
 */
class BhaaImport
{
	var $min = 0;
	var $max = 100;
	
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
		
		if(!empty($_GET['min']))
			$this->min = $_GET['min'];
		if(!empty($_GET['max']))
			$this->max = $_GET['max'];
		
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
		elseif($_GET['action']=='stories')
			$this->importStories();
		elseif($_GET['action']=='deleteStories')
			$this->deleteStories();
		elseif($_GET['action']=='companies')
			$this->importCompanies();
		elseif($_GET['action']=='deleteCompanies')
			$this->deleteCompanies();
		elseif($_GET['action']=='races')
			$this->importRaces();
		elseif($_GET['action']=='deleteRaces')
			$this->deleteRaces();
		elseif($_GET['action']=='raceResults')
			$this->importRaceResults();
		elseif($_GET['action']=='deleteRaceResults')
			$this->deleteRaceResults();
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
		echo '<a href="admin.php?import=bhaa&action=stories">Import BHAA Stories</a> - <a href="admin.php?import=bhaa&action=deleteStories">Delete Stories</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=companies">Import BHAA Companies</a> - <a href="admin.php?import=bhaa&action=deleteCompanies">Delete Companies</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=races">Import BHAA Races</a> - <a href="admin.php?import=bhaa&action=deleteRaces">Delete Races</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=raceResults">Import BHAA Race Results</a> - <a href="admin.php?import=bhaa&action=deleteRaceResults">Delete Race Results</a><br/>';
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
			//echo '<p>emLocation '.$emLocation->post_id.' '.$emLocation->location_id.'</p>';
				
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
			//echo '<p>emEvent '.$emEvent->post_id.' '.$emEvent->event_id.' '.$emLocation->location_id.'</p>';
			error_log('emEvent '.$emEvent->post_id.' '.$emEvent->event_name.' '.$emLocation->location_name);
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
        	$name = strtolower($user->firstname.'.'.$user->surname);
        	$name = str_replace(' ', '', $name);
        	$name = str_replace("'", '', $name);
        	$password = wp_hash_password($user->id);
        	
        	// DB insert user
        	$this->insertUser($user->id,$name,$password,$user->email);

        	// http://codex.wordpress.org/Function_Reference/wp_insert_user
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

	function importStories()
	{
		global $wpdb;
        $count = 0;
        $stories = $this->getStories();

        require_once( ABSPATH . 'wp-includes/post.php' );
        foreach($stories as $story)
		{
			// Create post object http://codex.wordpress.org/Function_Reference/wp_insert_post
			$my_post = array(
					'post_title' => $story->title,
					'post_content' => $story->introtext.' '.$story->bodytext,
					'post_status' => 'publish',
					'post_author' => 1,
					'comment_status' => 'closed',
					'ping_status' => 'closed',
					'post_date' => $story->date,
					'post_date_gmt' => $story->date,
					'post_type' => 'post',
//					'post_excerpt' => 'importer'
					'post_category' => array(3)
			);
			// Insert the post into the database
			$id = wp_insert_post( $my_post );
			
			$mgs = 'added post '.$id.' for story '.$story->title;
			echo '<p>'.$mgs.'</p>';
			error_log($mgs);
		}
	}
	
	function getStories()
	{
		$db = $this->getGlfusionDB();
		return $db->get_results($db->prepare(
			'select sid,date,introtext,bodytext,title from gl_stories')
		);
	}
	
	function deleteStories()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_posts WHERE post_author=1 and post_type='post' ")
		);
	}
	
	function importCompanies()
	{
		global $wpdb;
		$count = 0;
		$companies = $this->getCompanies();
	
		require_once( ABSPATH . 'wp-includes/post.php' );
		foreach($companies as $company)
		{
			$wpdb->query($wpdb->prepare(
					"INSERT INTO wp_posts(
					ID,
					post_type)
					VALUES (%d,%s)",
					$company->id,'company'));
			
			// Create post object http://codex.wordpress.org/Function_Reference/wp_insert_post
			$my_post = array(
					'ID' => $company->id,
					'post_title' => $company->name,//mysql_real_escape_string($company->name),
					'post_content' => $company->image,
					'post_status' => 'publish',
					'post_author' => 1,
					//'comment_status' => 'closed',
					//'ping_status' => 'closed',
					'post_date' => 'NOW()',// getdate(),
					'post_date_gmt' => 'NOW()',//getdate(),
					//'tags_input' => array($company->sector),
					'post_type' => 'company',
					'tax_input' => array( 'sector' => array($company->sectorname) )
					//'post_category' => array(4)
			);
			// Insert the post into the database
			$id = wp_insert_post( $my_post );
			update_post_meta($id,'bhaa_company_id',$company->id);
			update_post_meta($id,'bhaa_company_website',$company->website);
			update_post_meta($id,'bhaa_company_image',$company->image);
			
			$mgs = 'added post '.$id.' for company '.$company->name;
			echo '<p>'.$mgs.'</p>';
			error_log($mgs);
		}
	}
	
	/**
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=companies&min=50&max=149
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=companies&min=50&max=249
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=companies&min=250&max=449
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=companies&min=450&max=749
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=companies&min=750&max=999
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=companies&min=1000&max=1400
	 */
	function getCompanies()
	{
		$db = $this->getBhaaDB();
		$sql = $db->prepare('select c.id,c.name,sector,website,image,s.name as sectorname from company c join sector s on c.sector=s.id limit 10 order by c.id');
		if($this->min!=0||$this->max!=100)
			$sql = $db->prepare('select c.id,c.name,sector,website,image,s.name as sectorname from company c join sector s on c.sector=s.id where c.id>=%d and c.id<=%d order by c.id',$this->min,$this->max);
		echo '<p>'.$sql.'</p>';
		error_log($sql);
		return $db->get_results($sql);
	}
	
	function deleteCompanies()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_posts WHERE post_author=1 and post_type='company'")
		);
	}
	
	function getEvents()
	{
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare(
			'SELECT id,name,tag,date,location FROM event LIMIT %d',100)
		);
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
    	$db = $this->getBhaaDB();
    	return $db->get_results($db->prepare(
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
   			status ="M"'));
//   			id IN (%d, %d, %d, %d, %d, %d, %d, %d, %d)',
  //  			7713, 1050, 6349, 5143, 7905, 5738, 7396, 10137, 10143));
    		// status != 'D'"));
    		// ID IN (%d,%d)",7713,1050));
	}

	/**
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
	
	function importRaces()
	{
		global $wpdb;
		$count = 0;
		$list = $this->getRaces();
	
		require_once( ABSPATH . 'wp-includes/post.php' );
		foreach($list as $row)
		{
			// Create post object http://codex.wordpress.org/Function_Reference/wp_insert_post
			$my_post = array(
				'post_title' => 'race_'.$row->id,
				'post_content' => 'race_'.$row->id,
				'post_status' => 'publish',
				'post_author' => 1,
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_date' => getdate(),
				'post_date_gmt' => getdate(),
				'post_type' => 'race'
			);
			// Insert the post into the database
			$post_id = wp_insert_post( $my_post );
	
			update_post_meta($post_id,'bhaa_race_id',$row->id);
			update_post_meta($post_id,'bhaa_race_event',$row->event);
			update_post_meta($post_id,'bhaa_race_distance',$row->distance);
			update_post_meta($post_id,'bhaa_race_unit',$row->unit);
			update_post_meta($post_id,'bhaa_race_type',$row->type);
			update_post_meta($post_id,'bhaa_race_category',$row->category);
			
			$mgs = 'added post '.$post_id.' for race '.$row->id;
			echo '<p>'.$mgs.'</p>';
			error_log($mgs);
		}
	}
	
	function getRaces()
	{
		$db = $this->getBhaaDB();
		return $db->get_results($db->prepare(
			'SELECT id,event,starttime,distance,unit,category,type FROM race')// LIMIT %d',10)
		);
	}
	
	function deleteRaces()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			'DELETE FROM wp_posts WHERE post_type = %s','race')
		);
	}
	
	function importRaceResults()
	{
		global $wpdb;
		$count = 0;
		$list = $this->getRaceResults();
	
		global $wpdb;
		foreach($list as $row)
		{
			$wpdb->query(
				$wpdb->prepare(
					'INSERT INTO wp_bhaa_raceresult(
					race,
					runner,
					racetime,
					position,
					racenumber,
					category,
					standard,
					paceKM,
					class)
					VALUES (%d,%d,%s,%d,%d,%s,%d,%s,%s)',
					$row->race,
					$row->runner,
					$row->racetime,
					$row->position,
					$row->racenumber,
					$row->category,
					$row->standard,
					$row->paceKM,
					$row->class));
				$mgs = 'insert raceresult '.$row->race.' '.$row->runner;
				echo '<p>'.$mgs.'</p>';
				error_log($mgs);
		}
	}
	
	function getRaceResults()
	{
		$db = $this->getBhaaDB();
		return $db->get_results($db->prepare(
				'SELECT
				race,
				runner,
				racetime,
				position,
				racenumber,
				category,
				raceresult.standard,
				paceKM,
				class
				FROM raceresult 
				JOIN runner on runner.id=raceresult.runner
		runner IN (%d, %d, %d, %d, %d, %d, %d, %d, %d)',
		7713, 1500, 6349, 5143, 7905, 5738, 7396, 10137, 10143));
//				where runner.status="M" order by race desc'));
//		runner IN (%d, %d, %d, %d, %d, %d, %d, %d, %d)',
//		7713, 1050, 6349, 5143, 7905, 5738, 7396, 10137, 10143));
//		 status != 'M'"));
		// ID IN (%d,%d)",7713,1050));
	}
	
	function deleteRaceResults()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			'DELETE FROM wp_bhaa_raceresult')
		);
	}
	
	function getBhaaDB()
	{
		// user, pass, dbname, host
		return new wpdb('root','','bhaaie_members','localhost');
	}
	
	function getGlfusionDB()
	{
		// user, pass, dbname, host
		return new wpdb('root','','bhaaie_glfusion','localhost');
	}
}
?>