<?php
/**
 * Handles operation on runners.
 * TODO move some stuff to user class.
 * @author oconnellp
 *
 */
class Runner_Manager {
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {	
		add_action('admin_action_bhaa_runner_renew_action',array($this,'bhaa_runner_renew_action'));
		add_action('admin_action_bhaa_runner_rename_action',array($this,'bhaa_runner_rename_action'));
		add_action('admin_action_bhaa_runner_email_action',array($this,'bhaa_runner_email_action'));
		add_action('admin_action_bhaa_runner_dob_action',array($this,'bhaa_runner_dob_action'));
		add_action('admin_action_bhaa_runner_merge_action',array($this,'bhaa_runner_merge_action'));
	}
	
	/**
	 * Generate a renewal button for admin users via a shortcode
	 * @return string
	 */
	function renewal_button_shortcode() {
		if(current_user_can('edit_users')) {
			return '<form action="'.admin_url( 'admin.php' ).'" method="POST">'
				wp_nonce_field('bhaa_runner_renew_action').'
			    <input type="hidden" name="action" value="bhaa_runner_renew_action" />
				<input type="hidden" name="id" value="'.get_query_var('id').'"/>
				<input type="submit" value="Renew Runner"/>
				</form>';
		} else {
			return "";
		}
	}
	
	function bhaa_runner_renew_action() {
		if(wp_verify_nonce($_REQUEST['_wpnonce'], 'bhaa_runner_renew_action')) {
			$runner = new Runner($_POST['id']);
			$runner->renew();
		}
	}
	
	/**
	 * Return the runners name or edit form for the admin user
	 * @return string
	 */
	function bhaa_runner_name_shortcode() {
		$runner = new Runner(get_query_var('id'));
		if(current_user_can('edit_users')) {
			return '<div><form action="'.admin_url( 'admin.php' ).'" method="POST">'
				wp_nonce_field('bhaa_runner_rename_action').'
			    <input type="hidden" name="action" value="bhaa_runner_rename_action" />
				<input type="text" name="first_name" value="'.$runner->getFirstName().'"/>
				<input type="text" name="last_name" value="'.$runner->getLastName().'"/>
				<input type="hidden" name="id" value="'.get_query_var('id').'"/>
				<input type="submit" value="Rename"/>
				</form></div>';
		}
		else {
			return $runner->getFullName();
		}
	}
	
	function bhaa_runner_rename_action() {
		if(wp_verify_nonce($_REQUEST['_wpnonce'], 'bhaa_runner_rename_action')) {
			$first_name = trim($_POST['first_name']);
			wp_update_user( array ( 'ID' => $_POST['id'], 'first_name' => $first_name ) ) ;
			$last_name = trim($_POST['last_name']);
			wp_update_user( array ( 'ID' => $_POST['id'], 'last_name' => $last_name ) ) ;
			wp_update_user( array ('ID' => $_POST['id'], 'display_name' => $first_name." ".$last_name));
			wp_update_user( array ('ID' => $_POST['id'], 'user_nicename' =>  $first_name."-".$last_name));
		}
		wp_redirect(wp_get_referer());
		exit();
	}
	
	function bhaa_runner_email_shortcode() {
		$runner = new Runner(get_query_var('id'));
		if(current_user_can('edit_users')) {
			return '<div><form action="'.admin_url( 'admin.php' ).'" method="POST">'.
				wp_nonce_field('bhaa_runner_email_action').'
			    <input type="hidden" name="action" value="bhaa_runner_email_action"/>
				<input type="text" name="email" value="'.$runner->geUserEmail().'"/>
				<input type="hidden" name="id" value="'.get_query_var('id').'"/>
				<input type="submit" value="Email"/>
				</form></div>';
		}
		else {
			return "";
		}
	}
	
	function bhaa_runner_email_action() {
		if(wp_verify_nonce($_REQUEST['_wpnonce'], 'bhaa_runner_email_action')) {
			error_log('bhaa_runner_email_action '.$_POST['id'].' -> '.$_POST['email']);
			wp_update_user( array ( 'ID' => $_POST['id'], 'user_email' => trim($_POST['email']) ) ) ;
		}
		wp_redirect(wp_get_referer());
		exit();
	}
	
	function bhaa_runner_dob_shortcode(){
		$runner = new Runner(get_query_var('id'));
		if(current_user_can('edit_users')) {
			return '<div><form action="'.admin_url( 'admin.php' ).'" method="POST">'
				wp_nonce_field('bhaa_runner_dob_action').'
			    <input type="hidden" name="action" value="bhaa_runner_dob_action"/>
				<input type="text" name="dob" value="'.$runner->getDateOfBirth().'"/>
				<input type="hidden" name="id" value="'.get_query_var('id').'"/>
				<input type="submit" value="DateOfBirth"/>
				</form></div>';
		}
		else {
			return "";
		}
	}
	
	function bhaa_runner_dob_action() {
		if(wp_verify_nonce($_REQUEST['_wpnonce'], 'bhaa_runner_dob_action')) {
			error_log('bhaa_runner_dob_action '.$_POST['id'].' -> '.$_POST['dob']);
			update_user_meta($_POST['id'],'bhaa_runner_dateofbirth',trim($_POST['dob']));
		}
		wp_redirect(wp_get_referer());
		exit();
	}

	
	/**
	 * http://mattvarone.com/wordpress/list-users/
	 * http://wordpress.stackexchange.com/questions/38599/wordpress-wp-user-queryargs-using-where-and-like
	 * http://www.egstudio.biz/7-examples-for-using-wp-user-query/
	 * http://wordpress.stackexchange.com/questions/30977/list-users-by-last-name-in-wp-user-query
	 * http://forrst.com/posts/Frontend_user_search_by_first_and_last_name_in_W-re1
	 * http://codex.wordpress.org/Class_Reference/WP_User_Query
	 */
	function bhaa_runner_search() {
		//error_log('bhaawp_runner_search '.$_REQUEST['term']);
		$query = strip_tags( $_REQUEST['term'] );
	
		$suggestions=array();
		if(preg_match('/\d{4}|\d{5}/', $query))
		{
			// query on id
			$user = get_userdata($query);
			$suggestion = array();
			$suggestion['label'] = $user->display_name;
			$suggestion['link'] = sprintf('%s/runner/?id=%d',get_site_url(),$user->ID);
			$suggestions[]=$suggestion;
		}
		else
		{
			// query name
			$args = array(
					'number' => 10,
					'fields' => 'all',
					'meta_query' => array(
							'relation' => 'AND',
							array('key' => 'nickname','compare' => 'like', 'value' => $query),
							array('key' => 'bhaa_runner_status','compare'=>'!=','value'=>'D')
					)
			);
	
			error_log(print_r($args,true));
			$user_query = new WP_User_Query( $args );
			$runners = $user_query->get_results();
			if (!empty($runners))
			{
				foreach ($runners as $runner)
				{
					$runner_info = get_userdata($runner->ID);
					$suggestion = array();
					$suggestion['label'] = $runner_info->display_name;
					$suggestion['link'] = sprintf('%s/runner/?id=%d',get_site_url(),$runner_info->ID);
					$suggestions[]=$suggestion;
				}
			}
			wp_reset_postdata();
		}
		$response = json_encode(array('matches'=>$suggestions));
		//error_log('bhaawp_runner_search '.$response);
		echo $response;
		die();
	}
	
	/**
	 * user id - email x_id@bhaa.ie
	 * $sqlstat = "SHOW TABLE STATUS WHERE name='wp_users'";
	 * select max(id) from wp_users
	 *
	 $user_login = str_pad($wpdb->get_row($sqlstat)->Auto_increment , 5, 0, STR_PAD_LEFT);
	 */
	public function getNextRunnerId() {
		global $wpdb;
		$sqlstat = "SHOW TABLE STATUS WHERE name='wp_users'";
		return str_pad($wpdb->get_row($sqlstat)->Auto_increment , 5, 0, STR_PAD_LEFT);
	}
	
	/**
	 * Does runner exist
	 * @param unknown $id
	 * @return Ambigous <mixed, NULL, multitype:>
	 */
	public function runnerExists($id) {
		global $wpdb;
		return $wpdb->get_var($wpdb->prepare('select count(id) as isrunner from wp_users where id=%d',$id));
	}
	
	public function createNewUser($firstname,$surname,$email,$gender,$dateofbirth,$runner_id=NULL)
	{
		require_once( ABSPATH . 'wp-includes/user.php' );
	
		if($runner_id == '' || $runner_id == NULL) {
			$id = $this->getNextRunnerId();
			error_log('create new user with next id '.$id);
		} else {
			$id=$runner_id;
			error_log('import user '.$runner_id.' with id '.$id);
		}
	
		// format the username
		$username = $firstname.' '.$surname;
		$username = str_replace(' ', '', $username);
		$username = str_replace("'", '', $username);
	
		// check for a unique username
		$user_id = username_exists($username);
		if(!user_id) {
			$username = $username.$id;
		}
	
		if($email=='')
			$email = 'x'.$username.'@bhaa.ie';
	
		if($gender!='M')
			$gender='W';
	
		$password =  wp_hash_password($id);
	
		// insert the user via SQL
		$this->insertUser($id,$username,$password,$email);
		// update the wp_user
		$res = wp_update_user(array(
				'ID'            => $id,
				'user_login'    => $username,
				'user_email'    => $email,
				'nickname' => $username,
				'display_name'=> $username,
				'first_name' => $firstname,
				'last_name'=> $surname
		));
		if(is_wp_error($res))
			error_log('update user error '.$res->get_error_message());
	
		update_user_meta( $id, Runner::BHAA_RUNNER_GENDER, $gender);
		update_user_meta( $id, Runner::BHAA_RUNNER_DATEOFBIRTH, $dateofbirth);
		update_user_meta( $id, Runner::BHAA_RUNNER_INSERTDATE, date('Y-m-d'));
		update_user_meta( $id, Runner::BHAA_RUNNER_STATUS,'D');
		update_user_meta( $id, Runner::BHAA_RUNNER_COMPANY,1);
		update_user_meta( $id, Runner::BHAA_RUNNER_MOBILEPHONE,'0123456789');
		return $id;
	}
	
	private function insertUser($id,$name,$password,$email)
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
					display_name,
					user_registered)
					VALUES (%d,%s,%s,%s,%s,%d,%s,NOW())",
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
					display_name,
					user_registered)
					VALUES (%d,%s,%s,%s,%s,%s,NOW())",
					$id,$name,$password,$name,0,$name);
		}
		$wpdb->query($sql);
	}
	
	public function matchRunner($firstname,$surname,$dateofbirth)
	{
		$args = array(
				'number' => 1,
				'fields' => 'all',
				'meta_query' => array(
						'relation' => 'AND',
						array('key'=>'first_name','compare'=>'=','value'=>$firstname),
						array('key'=>'last_name','compare'=>'=','value'=>$surname),
						array('key'=>'bhaa_runner_status','compare'=>'=','value'=>'D'),
						array('key'=>Runner::BHAA_RUNNER_DATEOFBIRTH,'compare'=>'=','value'=>$dateofbirth)
				)
		);
		//error_log(print_r($args,true));
		$user_query = new WP_User_Query($args);
	
		$runner = $user_query->get_results();
		error_log($firstname.' '.$surname.' matches '+sizeof($runner).' '.$user_query->get_total());
		if(empty($runner))
		{
			//error_log('no match');
			return 0;
		}
		else
		{
			//error_log('matched runner '.print_r($runner,true));
			return $runner[0]->ID;
		}
	}

	/**
	 * Return a list of matching runners to this runner
	 * @return unknown
	 */
	function bhaa_runner_matches_shortcode() {
		// get current users details
		$user_info = get_userdata(get_query_var('id'));
		$bhaa_runner_dateofbirth = get_user_meta(get_query_var('id'),'bhaa_runner_dateofbirth',true);
	
		$queryMatchAll = new WP_User_Query(
			array(
				'exclude' => array(get_query_var('id')),
				'fields' => 'all_with_meta',
				'meta_query' => array(
					array(
							'key' => 'last_name',
							'value' => $user_info->user_lastname,
							'compare' => '='),
					array(
							'key' => 'first_name',
							'value' => $user_info->user_firstname,
							'compare' => '='),
					array(
							'key' => 'bhaa_runner_dateofbirth',
							'value' => $bhaa_runner_dateofbirth,
							'compare' => '='
					))));
	
		$queryMatchName = new WP_User_Query(
			array(
				'exclude' => array(get_query_var('id')),
				'fields' => 'all_with_meta',
				'meta_query' => array(
					array(
						'key' => 'last_name',
						'value' => $user_info->user_lastname,
						'compare' => '='),
					array(
						'key' => 'first_name',
						'value' => $user_info->user_firstname,
						'compare' => '='
					))));
	
		$queryMatchLastDob = new WP_User_Query(
			array(
				'exclude' => array(get_query_var('id')),
				'fields' => 'all_with_meta',
				'meta_query' => array(
					array(
							'key' => 'last_name',
							'value' => $user_info->user_lastname,
							'compare' => '='),
					array(
							'key' => 'bhaa_runner_dateofbirth',
							'value' => $bhaa_runner_dateofbirth,
							'compare' => '='
					))));
	
		// merge the three results	
		$users = array_merge( $queryMatchAll->get_results(), $queryMatchName->get_results(), $queryMatchLastDob->get_results());
		$table = '<div>';
		foreach($users as $matcheduser) {
			$table .= sprintf('<div>%d <a href="%s">%s</a> DOB:%s, Status:%s, Email:%s <form action="'
					.admin_url( 'admin.php' ).'" method="POST">'
					wp_nonce_field('bhaa_runner_merge_action').'
					<input type="hidden" name="action" value="bhaa_runner_merge_action"/>
					<input type="hidden" name="id" value="%d"/>
					<input type="hidden" name="merge" value="%d"/>
					<input type="submit" value="Delete %d and merge to %d"/>
					</form></div>',
				$matcheduser->ID,
				add_query_arg(array('id'=>$matcheduser->ID),'/runner'),$matcheduser->display_name,
				$matcheduser->bhaa_runner_dateofbirth,$matcheduser->bhaa_runner_status,$matcheduser->user_email,
				$matcheduser->ID,get_query_var('id'),
				$matcheduser->ID,get_query_var('id')
			);
		return $table;
		}
	}
	
	function bhaa_runner_merge_action() {
		if(wp_verify_nonce($_REQUEST['_wpnonce'], 'bhaa_runner_merge_action')) {
			$this->mergeRunner($_POST['id'],$_POST['merge']);
		}
		wp_redirect(wp_get_referer());
		exit();
	}
	
	function mergeRunner($runner,$deleteRunner) {
		error_log('deleting runner '.$deleteRunner.' and merging to '.$runner);
		global $wpdb;
		// update existing race results
		$wpdb->update(
			'wp_bhaa_raceresult',
			array('runner' => $runner),
			array('runner' => $deleteRunner)
		);
		// update team results
		$wpdb->update(
			'wp_bhaa_teamresult',
			array('runner' => $runner),
			array('runner' => $deleteRunner)
		);
		// wp_bhaa_leaguerunnerdata
		$wpdb->update(
			'wp_bhaa_leaguerunnerdata',
			array('runner' => $runner),
			array('runner' => $deleteRunner)
		);
		// wp_bhaa_leaguesummary
		$wpdb->update(
			'wp_bhaa_leaguesummary',
			array('leagueparticipant' => $runner),
			array('leagueparticipant' => $deleteRunner,'leaguetype'=>'I')
		);
		// update any bookings
		$wpdb->update(
			'wp_em_bookings',
			array('person_id' => $runner),
			array('person_id' => $deleteRunner)
		);

		// delete the user and metadata
		$wpdb->delete(
			'wp_usermeta',
			array('user_id' => $deleteRunner)
		);
		$wpdb->delete(
			'wp_users',
			array('ID' => $deleteRunner)
		);
		error_log('merged runner '.$deleteRunner.' to '.$runner);
	}
	
	function addNewMember($firstname,$lastname,$gender,$dateofbirth,$email='') {
		$match = $this->matchRunner($firstname,$lastname,$dateofbirth);
		if($match!=0) {
			error_log('matched existing runner '.$runner_id);
			return $match;
		} else {
			$newRunner = $this->createNewUser($firstname,$lastname,$email,$gender,$dateofbirth);
			error_log('created new runner '.$runner_id);
			return $newRunner;
		}
	}
}
?>