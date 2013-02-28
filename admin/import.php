<?php
set_time_limit(0);
/**
 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
 * https://github.com/scribu/wp-posts-to-posts/wiki/Creating-connections-programatically
 */
class BhaaImport
{
	var $min = 0;
	var $max = 100;
	
	const BHAA_EVENT_TAG = 'bhaa_event_tag';
	var $RACE_ID = '0';
	//var $EVENT_LIMIT = '10';
	//var $RACE_LIMIT = '40';
	
	function BhaaImport()
	{
		require_once( ABSPATH . 'wp-admin/includes/import.php' );
		require_once( ABSPATH . 'wp-content/plugins/posts-to-posts/core/api.php' );
		
		register_importer('bhaa', 'BHAA', __('BHAA Importer'), array (&$this,'import'));
	}
	
	/**
	 * http://core.trac.wordpress.org/attachment/ticket/3398/geeklog.php
	 * http://wordpress.org/support/topic/converting-geeklog-to-wordpress?replies=7
	 */
	public function import()
	{
		// set a longer timelimit
		set_time_limit(4*60);
		
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
		elseif($_GET['action']=='teams')
			$this->importTeams();
		elseif($_GET['action']=='deleteHouses')
			$this->deleteHouses();
		elseif($_GET['action']=='races')
			$this->importRaces();
		elseif($_GET['action']=='deleteRaces')
			$this->deleteRaces();
		elseif($_GET['action']=='raceResults')
			$this->importRaceResults();
		elseif($_GET['action']=='deleteRaceResults')
			$this->deleteRaceResults();
		elseif($_GET['action']=='topics')
			$this->importTopics();
		elseif($_GET['action']=='posts')
			$this->importPosts();
		elseif($_GET['action']=='deleteForum')
			$this->deleteForum();
		elseif($_GET['action']=='linkRunnersToHouses')
			$this->linkRunnersToHouses();
 		elseif($_GET['action']=='importStandards')
 			$this->importStandards();
  		elseif($_GET['action']=='importTeamTypes')
  			$this->importTeamTypes();
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
		echo '<a href="admin.php?import=bhaa&action=companies">Import Companies to Houses</a> - <a href="admin.php?import=bhaa&action=deleteCompanies">Delete Companies</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=teams">Import Teams to Houses</a> - <a href="admin.php?import=bhaa&action=deleteHouses">Delete Houses</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=races">Import BHAA Races</a> - <a href="admin.php?import=bhaa&action=deleteRaces">Delete Races</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=raceResults">Import BHAA Race Results</a> - <a href="admin.php?import=bhaa&action=deleteRaceResults">Delete Race Results</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=topics">Import BHAA Topics</a> - 
			<a href="admin.php?import=bhaa&action=posts">Import BHAA Posts</a> - 
				<a href="admin.php?import=bhaa&action=deleteForum">Delete Forum</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=linkRunnersToHouses">Link runners to houses</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=importStandards">Import Standards</a><br/>';
		echo '<a href="admin.php?import=bhaa&action=importTeamTypes">Import Team Types</a><br/>';
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
		global $wpdb;
		$events = $this->getEvents();
		require_once( ABSPATH . 'wp-content/plugins/events-manager/classes/em-event.php' );
		require_once( ABSPATH . 'wp-content/plugins/events-manager/classes/em-location.php' );
		$count=0;
		foreach($events as $event)
		{
			$count++;
	
			// default location 1
// 			$emLocation = new EM_Location();
// 			$emLocation->location_owner = 1;
// 			$emLocation->location_name = $event->location;
// 			$emLocation->location_slug = $event->tag;
// 			$emLocation->location_address = $event->location;
// 			$emLocation->location_status = 'publish';
// 			$emLocation->location_town = 'Dublin';
// 			$emLocation->location_country = 'IE';
// 			$emLocation->post_title = $event->tag.' '.$event->location;
// 			$emLocation->post_name = $event->tag;
// 			$emLocation->save();
			//echo '<p>emLocation '.$emLocation->post_id.' '.$emLocation->location_id.'</p>';
				
			$emEvent = new EM_Event();
			$emEvent->event_name = $event->name.' '.$event->year; // append the year
			$emEvent->event_slug = $event->tag;
			$emEvent->event_owner = 1;
			$emEvent->event_start_date = $event->date;
			$emEvent->event_end_date = $event->date;
			$emEvent->event_all_day = 0;
			$emEvent->event_start_time = '11:00:00';
			$emEvent->event_end_time = '12:00:00';
			$emEvent->post_content = $event->name;//.' - '.$event->tag;
			$emEvent->event_status = 'publish';
			$emEvent->event_date_created = date('Y-m-d H:i:s');
			//$emEvent->location_id=$emLocation->location_id;
			$emEvent->save();
			
			update_post_meta($emEvent->post_id,BhaaImport::BHAA_EVENT_TAG,$event->tag);
			
			$wpdb->query(
				$wpdb->prepare(
					"insert into wp_bhaa_import(id,tag,type,new,old)
					VALUES (%d,%s,%s,%d,%d)",
					0,$event->tag,'event',$emEvent->post_id,$event->id)
			);
				
			
			//echo '<p>emEvent '.$emEvent->post_id.' '.$emEvent->event_id.' '.$emLocation->location_id.'</p>';
			error_log('emEvent '.$emEvent->post_id.' '.$emEvent->event_name);//.' '.$emLocation->location_name);
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

        require_once( ABSPATH . 'wp-includes/pluggable.php' );
        require_once( ABSPATH . 'wp-includes/user.php' );
        foreach($users as $user)
        {

        	$count++;
        	
        	
        	$wpuser = get_userdata( $user->id );
        	error_log($count.' '.$user->id.' '. empty($wpuser->ID));
        	
        	/**
        	 * http://codex.wordpress.org/Function_Reference/wp_insert_user
        	 * http://codex.wordpress.org/Function_Reference/wp_create_user
        	 */
        	// DB insert user
        	$name = strtolower($user->firstname.'.'.$user->surname);
        	$name = str_replace(' ', '', $name);
        	$name = str_replace("'", '', $name);
        	
        	if(empty($wpuser->ID))
        	{
        		$password = wp_hash_password($user->id);
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
        	}
			else
				error_log('update meta data only '.$wpuser->ID);
			

								
			if ( is_wp_error($id) )
			{
				error_log($id->get_error_message());
				echo $id->get_error_message();
        	}
			update_user_meta( $id, 'rich_editing', 'false');
			if(isset($user->gender))
				update_user_meta( $id, Runner::BHAA_RUNNER_GENDER, $user->gender);
			if(isset($user->dateofbirth))
				update_user_meta( $id, Runner::BHAA_RUNNER_DATEOFBIRTH, $user->dateofbirth);
			if(isset($user->company))
			{
				update_user_meta( $id, Runner::BHAA_RUNNER_COMPANY, $user->company);
// 				p2p_type( Connection::HOUSE_TO_RUNNER )->connect( 
// 						$user->company, 
// 						$user->id, 
// 						array('date' => current_time('mysql')
// 				) );
        	}
			//if(isset($user->companyname))
				//update_user_meta( $id, 'bhaa_runner_companyname', $user->companyname);			
			// 	team,
			if(isset($user->newsletter))
				update_user_meta( $id, Runner::BHAA_RUNNER_NEWSLETTER, $user->newsletter);
			//         	telephone,
			//if(isset($user->telephone))
			//	update_user_meta( $id, 'bhaa_runner_telephone', $user->telephone);
			//	mobilephone,
			if(isset($user->mobilephone))
				update_user_meta( $id, Runner::BHAA_RUNNER_MOBILEPHONE, $user->mobilephone);
			//         	textmessage,
			if(isset($user->textmessage))
				update_user_meta( $id, Runner::BHAA_RUNNER_TEXTALERT, $user->textmessage);
			//         	address1,
			if(isset($user->address1))
				update_user_meta( $id, Runner::BHAA_RUNNER_ADDRESS1, $user->address1);
			//         	address2,
			if(isset($user->address2))
				update_user_meta( $id, Runner::BHAA_RUNNER_ADDRESS2, $user->address2);
			//         	address3,
			if(isset($user->address3))
				update_user_meta( $id, Runner::BHAA_RUNNER_ADDRESS3, $user->address3);
			//         	status,
			if(isset($user->status))
				update_user_meta( $id, Runner::BHAA_RUNNER_STATUS, $user->status);
			// insertdate,
			if(isset($user->insertdate))
				update_user_meta( $id, Runner::BHAA_RUNNER_INSERTDATE, $user->insertdate);
			//         	dateofrenewal
			if(isset($user->dateofrenewal))
				update_user_meta( $id, Runner::BHAA_RUNNER_DATEOFRENEWAL, $user->dateofrenewal);
			// standard
			if(isset($user->standard))
				update_user_meta( $id, Runner::BHAA_RUNNER_STANDARD, $user->standard);
			
			$msg = $id.' '.$name.' '.$user->standard;
			echo '<p>'.$msg.'</p>';
			error_log($msg);
			
			//$u = new WP_User( $id );
			//$u->add_role( 'subscriber' );
        }
	}
	
	/**
	 * http://localhost/wp-admin/admin.php?import=bhaa&action=importStandards&min=5000&max=6000
	 */
	function importStandards()
	{
		$select = 'SELECT id, status, dateofrenewal, standard FROM runner ';
		$db = $this->getBhaaDB();
		if($this->min!=0||$this->max!=100)
		$sql = $db->prepare($select.'where id>=%d and id<=%d order by id',$this->min,$this->max);
		echo '<p>'.$sql.'</p>';
		error_log($sql);
		require_once( ABSPATH . 'wp-includes/user.php' );
		$users = $db->get_results($sql);
		foreach($users as $user)
		{
			error_log($user->id.', Std:'.$user->standard.', D:'.$user->dateofrenewal.'. S:'.$user->status);
			
			if(isset($user->dateofrenewal))
				update_user_meta( $user->id, Runner::BHAA_RUNNER_STATUS, $user->status);
			if(isset($user->dateofrenewal))
				update_user_meta( $user->id, Runner::BHAA_RUNNER_DATEOFRENEWAL, $user->dateofrenewal);
			if(isset($user->standard))
				update_user_meta( $user->id, Runner::BHAA_RUNNER_STANDARD, $user->standard);
		}
	}
	
	function importTeamTypes()
	{
		$db = $this->getBhaaDB();
		$select = 'SELECT id, name, type, contact, status FROM team';
		$sql = $db->prepare($select);
		echo '<p>'.$sql.'</p>';
		error_log($sql);
		$teams = $db->get_results($sql);
		foreach($teams as $team)
		{
			$msg = $team->id.', name:'.$team->name.', type:'.$team->type.'. S:'.$team->status.', contact:'.$team->contact;
			$this->displayAndLog($msg);
			if( ($team->status=="ACTIVE") && ($team->type=="S") )
			{
				$res = wp_set_post_terms($team->id, HouseCpt::SECTOR_TEAM, HouseCpt::TEAM_TYPE,false);
				error_log(print_r($res));
			}
			elseif( ($team->status=="ACTIVE") && ($team->type=="C") )
			{
				$res = wp_set_post_terms($team->id, HouseCpt::COMPANY_TEAM, HouseCpt::TEAM_TYPE,false);
				error_log(print_r($res));
			}
			$res = wp_set_post_terms($team->id,House::TEAM_STATUS,$team->status,false);
			error_log(print_r($res));
			
			if($team->contact!='' || $team->contact != NULL)
			{
				$res = p2p_type(Connection::TEAM_CONTACT)->connect(
					$team->id,
					$team->contact,
					array('date' => current_time('mysql'))
				);
				$this->displayAndLog('Team contact '.$team->id.','.$team->contact);
			}
		}
	}
	
	function displayAndLog($msg,$display=true,$log=true)
	{
		if($display)
			echo '<p>'.$msg.'</p>';
		if($log)
			error_log($msg);
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
	
	function importTopics()
	{
		global $wpdb;
		$count = 0;
		$threads = $this->getForumThreads();
		
		foreach($threads as $thread)
		{
			// INSERT INTO `wp_forum_threads`(`id`, `parent_id`, `views`, `subject`, `date`, `status`, `closed`, `mngl_id`, `starter`, `last_post`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10])
			$wpdb->query($wpdb->prepare(
				"insert into wp_forum_threads(id,parent_id,views,subject,date,status,closed,mngl_id,starter,last_post)
				VALUES (%d,%d,%d,%s,%s,%s,%d,%d,%d,%s)",
				$thread->id,1,$thread->views,$thread->subject,$thread->date,'open',1,-1,$thread->bhaa_runner_id,$thread->date));
			
			$wpdb->query($wpdb->prepare(
				"insert into wp_forum_posts(id,text,parent_id,date,author_id,subject,views)
				VALUES (%d,%s,%d,%s,%d,%s,%d)",
				$thread->id,$thread->comment,$thread->id,$thread->date,$thread->bhaa_runner_id,$thread->subject,$thread->views));
		}		
		$mgs = 'added topics';
		echo '<p>'.$mgs.'</p>';
		error_log($mgs);
	}
	
	function importPosts()
	{
		global $wpdb;
		$count = 0;
		$posts = $this->getForumPosts();
		
		foreach($posts as $post)
		{
			$wpdb->query($wpdb->prepare(
				"insert into wp_forum_posts(text,parent_id,date,author_id,subject,views)
				VALUES (%s,%d,%s,%d,%s,%d)",
				$post->comment,$post->pid,$post->date,$post->bhaa_runner_id,$post->subject,$post->views));
		}
		$mgs = 'added posts';
		echo '<p>'.$mgs.'</p>';
		error_log($mgs);
	}
	
	function getForumThreads()
	{
		$db = $this->getGlfusionDB();
		return $db->get_results(
			$db->prepare('select id,pid,uid,FROM_UNIXTIME(date) as date,subject,comment,views,bhaa_runner_id
				from gl_forum_topic join gluser_bhaarunner on 
				gluser_bhaarunner.gl_users_id=gl_forum_topic.uid where pid=0 group by id'));		
	}
	
	function getForumPosts()
	{
		$db = $this->getGlfusionDB();
		return $db->get_results(
			$db->prepare('select id,pid,uid,FROM_UNIXTIME(date) as date,subject,comment,views,bhaa_runner_id
				from gl_forum_topic left join gluser_bhaarunner on
				gluser_bhaarunner.gl_users_id=gl_forum_topic.uid where pid!=0'));
	}
	
	function deleteForum()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM wp_forum_threads"));
		$wpdb->query($wpdb->prepare("DELETE FROM wp_forum_posts"));
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
					$company->id,'house'));
			
			// Create post object http://codex.wordpress.org/Function_Reference/wp_insert_post
			$my_post = array(
					'ID' => $company->id,
					'post_title' => $company->name,//mysql_real_escape_string($company->name),
					'post_content' => $company->name,
					'post_status' => 'publish',
					'post_author' => 1,
					'comment_status' => 'closed',
					'ping_status' => 'closed',
					'post_date' => 'NOW()',// getdate(),
					'post_date_gmt' => 'NOW()',//getdate(),
					//'tags_input' => array($company->sector),
					'post_type' => 'house',
					'tax_input' => array( 'sector' => array($company->sectorname) )
					//'post_category' => array(4)
			);
			// Insert the post into the database
			$id = wp_insert_post( $my_post );
			//update_post_meta($id,'bhaa_company_id',$company->id);
			update_post_meta($id,'bhaa_company_website',$company->website);
			update_post_meta($id,'bhaa_company_image',$company->image);
			//update_post_meta($id,House::BHAA_HOUSE_TYPE,House::COMPANY);
			$mgs = 'added post '.$id.' for house '.$company->name;
			echo '<p>'.$mgs.'</p>';
			error_log($mgs);
		}
	}
	
	/**
	 * import bhaa sector teams as a sector houses with a team
	 */
	function importTeams()
	{
		//echo "TODO - get the sector team details and create houses team";
		global $wpdb;
		$count = 0;
		$teams = $this->getTeams();
		
		require_once( ABSPATH . 'wp-includes/post.php' );
		foreach($teams as $team)
		{
			$wpdb->query($wpdb->prepare(
					"INSERT INTO wp_posts(
					ID,
					post_type)
					VALUES (%d,%s)",
					$team->id,'house'));
				
			// Create post object http://codex.wordpress.org/Function_Reference/wp_insert_post
			$my_post = array(
					'ID' => $team->id,
					'post_title' => $team->name,//mysql_real_escape_string($company->name),
					'post_content' => $team->name,
					'post_status' => 'private',
					'post_author' => 1,
					'comment_status' => 'closed',
					'ping_status' => 'closed',
					'post_date' => 'NOW()',// getdate(),
					'post_date_gmt' => 'NOW()',//getdate(),
					//'tags_input' => array($company->sector),
					'post_type' => 'house',
					'tax_input' => array( 'sector' => array($team->sectorname) )
					//'post_category' => array(4)
			);
			// Insert the post into the database
			$id = wp_insert_post( $my_post );
			//update_post_meta($id,'bhaa_company_id',$team->id);
			//update_post_meta($id,'bhaa_company_website',$company->website);
			//update_post_meta($id,'bhaa_company_image',$company->image);
			//update_post_meta($id,House::BHAA_HOUSE_TYPE,House::SECTORTEAM);
			$mgs = 'added post '.$id.' for sector team house '.$team->name;
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
		$sql = $db->prepare('select c.id,c.name,sector,website,image,s.name as sectorname from company c join sector s on c.sector=s.id order by c.id');
		if($this->min!=0||$this->max!=100)
			$sql = $db->prepare('select c.id,c.name,sector,website,image,s.name as sectorname from company c join sector s on c.sector=s.id where c.id>=%d and c.id<=%d order by c.id',$this->min,$this->max);
		echo '<p>'.$sql.'</p>';
		error_log($sql);
		return $db->get_results($sql);
	}
	
	function getTeams()
	{
		$db = $this->getBhaaDB();
		$sql = $db->prepare('
			select team.id,team.name,sector.name as sectorname from team
				join sector on sector.id=team.parent
				where type="S" order by id asc');
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
		// TODO delete links to users
	}
	
	/**
	 * delete houses cpt's
	 */
	function deleteHouses()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_posts WHERE post_type='house'")
		);
		// TODO delete links to users
	}
	
	function getEvents()
	{
		$db = $this->getBhaaDB();
		return $db->get_results($db->prepare(
			'SELECT id,name,tag,date,YEAR(date) as year,location FROM event order by id')// LIMIT '.$this->EVENT_LIMIT)
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
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_postmeta WHERE meta_key=%s",BhaaImport::BHAA_EVENT_TAG)
		);
		$wpdb->query($wpdb->prepare(
			'DELETE FROM wp_bhaa_import WHERE type="event"')
		);
	}
	
	/**
	 * 
	 * admin.php?import=bhaa&action=users&min=1500&max=3000
	 * admin.php?import=bhaa&action=users&min=3000&max=4000
	 * admin.php?import=bhaa&action=users&min=4000&max=5000
	 * admin.php?import=bhaa&action=users&min=5000&max=6000
	 * admin.php?import=bhaa&action=users&min=6000&max=6000
	 * admin.php?import=bhaa&action=users&min=7000&max=6000
	 * admin.php?import=bhaa&action=users&min=8000&max=6000
	 * admin.php?import=bhaa&action=users&min=9000&max=9999
	 * 
	 * TODO get the team membership details
	 * @return Ambigous <mixed, NULL, multitype:, multitype:multitype: , multitype:unknown >
	 */
	function getUsers()
    {
    	$select = 'SELECT 
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
    			dateofrenewal,
    			standard
    		FROM runner ';
    	
    	$db = $this->getBhaaDB();
    	//$sql = $db->prepare($select.'where id IN (%d, %d, %d, %d, %d, %d, %d, %d) order by id',
  			//1050, 6349, 5143, 7905, 5738, 7396, 10137, 10143);
    	if($this->min!=0||$this->max!=100)
    		$sql = $db->prepare($select.'where id>=%d and id<=%d order by id',$this->min,$this->max);
    	echo '<p>'.$sql.'</p>';
    	error_log($sql);
    	return $db->get_results($sql);
	}

	/**
	 * @param unknown_type $id
	 * @param unknown_type $name
	 */
	function insertUser($id,$name,$password,$email)
	{
		global $wpdb;
		$sql;
		
		//if(!get_user_by('id',$id))
		//{
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
			//error_log($id.' SQL '.$sql);
			$wpdb->query($sql);
// 		}
// 		else
// 		{
// 			error_log($id.' user exists');			
// 		}
	}
	
	function deleteUsers()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_users where ID!=%d and ID!=%d",1,7713));
		$wpdb->query($wpdb->prepare(
			"DELETE FROM wp_usermeta WHERE user_id!=%d and user_id!=%d",1,7713));
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
				'post_title' => $row->tag.'_'.$row->category.'_'.$row->distance.$row->unit,
				'post_content' => $row->tag.'_'.$row->category.'_'.$row->distance.$row->unit,
				'post_status' => 'publish',
				'post_author' => 1,
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_date' => $row->date,//'NOW()',// getdate(),
				'post_date_gmt' => $row->date,//'NOW()',// getdate(),
				'post_type' => 'race'
			);
			// Insert the post into the database
			$race_id = wp_insert_post( $my_post );
	
			//update_post_meta($race_id,'bhaa_event_tag',$row->tag);
			//update_post_meta($race_id,'bhaa_race_id',$row->id);
			//update_post_meta($race_id,'bhaa_race_event',$row->event);
			update_post_meta($race_id,'bhaa_race_distance',$row->distance);
			update_post_meta($race_id,'bhaa_race_unit',$row->unit);
			update_post_meta($race_id,'bhaa_race_type',$row->type);
			update_post_meta($race_id,'bhaa_race_category',$row->category);
			
			$mgs = 'added post '.$race_id.' for race '.$row->id;
			
			// use the BHAA_EVENT_TAG meta tag to lookup the correct event id
			$dbRow = $wpdb->get_row($wpdb->prepare(
				'SELECT post_id from wp_postmeta where meta_key="'.BhaaImport::BHAA_EVENT_TAG.'" and meta_value="%s"',$row->tag)
			);
			echo '<p>event '.$dbRow->post_id.'</p>';
			
			// link event and race
			p2p_type( 'event_to_race' )->connect(
					$dbRow->post_id,
					$race_id,
					array('date' => current_time('mysql')
				));
			
			// link old bhaa race id and new race id
			$wpdb->query(
				$wpdb->prepare(
					"insert into wp_bhaa_import(id,tag,type,new,old)
					VALUES (%d,%s,%s,%d,%d)",
					0,$row->tag,'race',$race_id,$row->id)
			);
			
			echo '<p>'.$mgs.'</p>';
			error_log($mgs);
		}
	}
	
	function getRaces()
	{
		$db = $this->getBhaaDB();
		return $db->get_results($db->prepare(
			'SELECT race.id,race.event,event.tag,starttime,distance,unit,category,race.type,event.date '.
			'FROM race join event on race.event=event.id order by race.id')// LIMIT '.$this->RACE_LIMIT)// LIMIT %d',10)
		);
	}
	
	function deleteRaces()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			'DELETE FROM wp_posts WHERE post_type = %s','race')
		);
		$wpdb->query($wpdb->prepare(
			'DELETE FROM wp_bhaa_import WHERE type="race"')
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
					pace,
					class)
					VALUES ((select new from wp_bhaa_import where old=%d and type="race"),%d,%s,%d,%d,%s,%d,%s,%s)',
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
				//echo '<p>'.$mgs.'</p>';
				error_log($mgs);
		}
		echo '<p>Done</p>';
	}
	
	function linkRunnersToHouses()
	{
		global $wpdb;
		$count = 0;
		echo '<p>'.__('Linking runners to houses...').'<br /><br /></p>';
		
		$users = $this->getRunnerTeamDetails();
		
		$connection = new Connection();
		foreach($users as $user)
		{
			$count++;
			// company team runner
			if($user->type=="C")// $user->company == $user->team)
			{
				$res = $connection->updateRunnersHouse(Connection::HOUSE_TO_RUNNER,$user->company,$user->runner);
// 				$res = p2p_type(Connection::HOUSE_TO_RUNNER)->connect(
// 					$user->company,
// 					$user->runner,
// 					array('date' => current_time('mysql'))
// 				);
				if ( is_wp_error($res) )
					$this->displayAndLog($res->get_error_message());
				else
					$this->displayAndLog($res.' Company '.$user->runner.' '.$user->companyname.' '.$user->teamname);
			}
			else
			{
				
				// link the sector team runner to both
				$res = $connection->updateRunnersHouse(Connection::HOUSE_TO_RUNNER,$user->company,$user->runner);
// 				$re = p2p_type(Connection::HOUSE_TO_RUNNER )->connect(
// 					$user->company,
// 					$user->runner,
// 					array('date' => current_time('mysql'))
// 				);
				if ( is_wp_error($re) )
					$this->displayAndLog($re->get_error_message());

				$res = $connection->updateRunnersHouse(Connection::SECTORTEAM_TO_RUNNER,$user->team,$user->runner);
// 				$res = p2p_type(Connection::SECTORTEAM_TO_RUNNER)->connect(
// 					$user->team,
// 					$user->runner,
// 					array('date' => current_time('mysql'))
// 				);
				if ( is_wp_error($res) )
					$this->displayAndLog($res->get_error_message());
				else
					$this->displayAndLog('sector team '.$user->runner.' '.$user->companyname.' '.$user->teamname);
			}		
		}
	}
	
	function getRunnerTeamDetails()
	{
		$db = $this->getBhaaDB();
		return $db->get_results(
			$db->prepare(
				'select runner.id as runner,runner.company,runner.companyname,team.id as team,team.name as teamname,team.type
				from runner
				join teammember on teammember.runner=runner.id
				join team on team.id=teammember.team
				where team.type="S" and runner.status!="D"')
//				where runner.status!="D" and runner.company!=0 order by runner.id')
		);
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
				where raceresult.race>='.$this->RACE_ID.' and runner.status="M" and class="RAN" order by raceresult.race'));
//		where runner.id IN (%d, %d, %d, %d, %d, %d, %d, %d, %d)',
//		7713, 1500, 6349, 5143, 7905, 5738, 7396, 10137, 10143));
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
		return new wpdb(
			get_option('bhaa_import_username'),
			get_option('bhaa_import_password'),
			'bhaaie_members','localhost');
	}
	
	function getGlfusionDB()
	{
		// user, pass, dbname, host
		return new wpdb(
			get_option('bhaa_import_username'),
			get_option('bhaa_import_password'),
			'bhaaie_glfusion','localhost');
	}
}
?>