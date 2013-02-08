<?php
/**
 * The runner class will handle user registration and editing of BHAA specific meta-data fields.
 * 
 * users will use email to login
 * username will be firstname.surname
 * 
 * @author oconnellp
 * 
 * -- add bhaa fields to registration
 * http://wordpress.org/support/topic/howto-custom-registration-fields
 * http://www.tutorialstag.com/create-custom-wordpress-registration-page.html
 *
 * -- use email as the login (we'll set the username based on first last name
 * http://wordpress.stackexchange.com/questions/51678/how-to-login-with-email-only-no-username
 * 
 * http://trepmal.com/action_hook/register_form/
 * http://www.rarescosma.com/2011/04/wordpress-hooks-user_register/
 * 
 * -- show bhaa logo on the login/registration page
 * http://www.paulund.co.uk/change-wordpress-login-logo-without-a-plugin
 * 
 * -- admin user columns
 * http://w4dev.com/wp/add-last-login-time-in-wp-admin-users-column/
 * 
 * http://stackoverflow.com/questions/9792418/how-do-i-select-user-id-first-and-last-name-directly-from-wordpress-database
 
 http://mattvarone.com/wordpress/list-users-with-wp_user_query/ 
 http://codex.wordpress.org/Class_Reference/WP_User_Query
 http://wp.smashingmagazine.com/2012/06/05/front-end-author-listing-user-search-wordpress/
 
 CIMY
 http://wordpress.org/extend/plugins/cimy-user-extra-fields/developers/
 http://blog.ftwr.co.uk/archives/2009/07/19/adding-extra-user-meta-fields/
 
 	// USER SEARCH
	
	// widget - http://plugins.svn.wordpress.org/advanced-search-widget/tags/0.2/advanced-search-widget.php
	
	// http://wp.smashingmagazine.com/2012/06/05/front-end-author-listing-user-search-wordpress/
//	http://maorchasen.com/blog/2012/09/19/using-wp_user_query-to-get-a-user-by-display_name/
//  http://www.tomauger.com/2012/tips-and-tricks/expanded-user-search-in-wordpress	


 */
class Runner
{	
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_FIRSTNAME = 'bhaa_runner_firstname';
	const BHAA_RUNNER_LASTNAME = 'bhaa_runner_lastname';
	
	const BHAA_RUNNER_DATEOFBIRTH = 'bhaa_runner_dateofbirth';
	const BHAA_RUNNER_COMPANY = 'bhaa_runner_company';
	const BHAA_RUNNER_NEWSLETTER = 'bhaa_runner_newsletter';
	const BHAA_RUNNER_TEXTALERT = 'bhaa_runner_textalert';
	const BHAA_RUNNER_MOBILEPHONE = 'bhaa_runner_mobilephone';
	const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	const BHAA_RUNNER_TERMS_AND_CONDITIONS = 'bhaa_runner_terms_and_conditions';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	const BHAA_RUNNER_GENDER = 'bhaa_runner_gender';
	const MAN = 'M';
	const WOMAN = 'W';
	
	const BHAA_RUNNER_STANDARD = 'bhaa_runner_standard';
	
	function __construct()
	{
	}
	
	function bhaa_runner_search() {
		//error_log('bhaawp_runner_search '.$_REQUEST['term']);
// 		$posts = get_posts( array(
// 				's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
// 				'post_type' => 'house'
// 		) );
// 		$suggestions=array();
	
// 		global $post;
// 		foreach ($posts as $post):
// 		setup_postdata($post);
// 		$suggestion = array();
// 		$suggestion['label'] = esc_html($post->post_title);
// 		$suggestion['link'] = get_permalink();
// 		$suggestions[]= $suggestion;
// 		endforeach;
	
		$suggestions=array();
		$suggestion = array();
		$suggestion['label'] = "POC-7713";
		$suggestion['link'] = 'http://localhost/runner/?id=7713';
		
		$suggestion1 = array();
		$suggestion1['label'] = "POC-2";
		$suggestion1['link'] = 'http://localhost/runner/?id=7713';
		
		$suggestions[]= $suggestion1;
		$response = json_encode(array('matches'=>$suggestions));
		//error_log('bhaawp_runner_search '.$response);
		echo $response;
		//wp_reset_postdata();
		//die();
		//exit;
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
	
	public function createNewUser($firstname,$surname,$email)
	{
		require_once( ABSPATH . 'wp-includes/user.php' );
		$id = $this->getNextRunnerId();
		error_log('next id '.id);
		
		$username = $firstname.'.'.$surname;
		if($email='')
			$email = $id.'@bhaa.ie';
		
		$password =  wp_hash_password($id);
		$user = wp_create_user($username, $password);
		error_log('new user id '.$user);
		return $user;
	}
}
?>