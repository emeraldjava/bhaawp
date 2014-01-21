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
	
	
		error_log('bhaawp_runner_search '.$_REQUEST['term']);
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
	public function getNextRunnerId()
	{
		global $wpdb;
		$sqlstat = "SHOW TABLE STATUS WHERE name='wp_users'";
		return str_pad($wpdb->get_row($sqlstat)->Auto_increment , 5, 0, STR_PAD_LEFT);
	}
	
	/**
	 * Does runner exist
	 * @param unknown $id
	 * @return Ambigous <mixed, NULL, multitype:>
	 */
	public function runnerExists($id)
	{
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
		$username = $firstname.'.'.$surname;
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
	
		update_user_meta( $id, Runner::BHAA_RUNNER_COMPANY, 1);
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
	
	public function mergeRunner($runner,$deleteRunner)
	{
		error_log('merging runner '.$merge.' to '.$runner);
		global $wpdb;
		// update existing race results
		error_log('moved raceresults '.$wpdb->update(
		'wp_bhaa_raceresult',
		array('runner' => $runner),
		array('runner' => $deleteRunner)
		));
		error_log('deleted metadata '.$wpdb->delete(
		'wp_usermeta',
		array('user_id' => $deleteRunner)
		));
		error_log('deleted user '.$wpdb->delete(
		'wp_users',
		array('ID' => $deleteRunner)
		));
		//require_once( ABSPATH . 'wp-admin/wp-includes/user.php' );
		//wp_delete_user($merge, $runner);
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