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

class Runner {
		
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	//const BHAA_RUNNER_FIRSTNAME = 'bhaa_runner_firstname';
	//const BHAA_RUNNER_LASTNAME = 'bhaa_runner_lastname';
	
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
	
	// make available for the house report via the mustache template engine.
	var $user_data;
	//var $company;	
	//var $sectorteam;
	
	/**
	 * Construct a runner with a specific id
	 * @param unknown $id
	 * 
	 * http://codebyjeff.com/blog/2014/01/a-cleaner-wp-user-object
	 */
	function __construct($user_id) {
		//$this->user = get_userdata($user_id);
		$user = (array) @get_user_by( 'id', $user_id )->data;
		//var_dump('user:'.print_r($this->user,true));
		
		//$user_meta = get_user_meta($user_id);//,"",false);
		$meta = @array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );
		//var_dump('meta:'.print_r($this->meta,true));
		
		// @
		$this->user_data = @array_merge($user, $meta);
		
		// https://github.com/scribu/wp-posts-to-posts/wiki/Checking-specific-connections
		$sectorteam = p2p_get_connections(Connections::SECTORTEAM_TO_RUNNER,array(
			'direction' => 'all',
			'to' => $user_id,
			'fields' => 'p2p_from'
		));
		//error_log('sector '.print_r($sectorteam,true));
		$this->user_data = @array_merge($this->user_data, array('sectorteam'=>$sectorteam[0]) );
		
		$company = p2p_get_connections(Connections::HOUSE_TO_RUNNER,array(
			'direction' => 'all',
			'to' => $user_id,
			'fields' => 'p2p_from'
		));
		//error_log('company '.print_r($company,true));
		$this->user_data = @array_merge($this->user_data, array('company'=>$company[0]));
		
		//var_dump('user_data:'.print_r($this->user_data,true));
	}

	function __get($var) {
		if (!array_key_exists($var, $this->user_data)){
			return null;//$this->default;
		}
	    return $this->user_data[$var];		
	}
	
	function getID() {
		return $this->__get('ID');
	}
	
	function getUserEmail() {
		return $this->__get('user_email');
	}

	function getFirstName() {
		return $this->first_name;
	}
	
	function getLastName() {
		return $this->last_name;
	}
	
	function getFullName() {
		return $this->getFirstName().' '.$this->getLastName();
	}
	
	function getDateOfBirth() {
		return $this->__get(Runner::BHAA_RUNNER_DATEOFBIRTH);
	}
	
	function getMobile() {
		return $this->__get(Runner::BHAA_RUNNER_MOBILEPHONE);
	}
	
	function getStandard() {
		return $this->__get(Runner::BHAA_RUNNER_STANDARD);
	}
	
	function getGender() {
		return $this->__get(Runner::BHAA_RUNNER_GENDER);
	}
	
	function getStatus() {
		return $this->__get(Runner::BHAA_RUNNER_STATUS);
	}
	
	function getDateOfRenewal() {
		return $this->__get(Runner::BHAA_RUNNER_DATEOFRENEWAL);
	}
	
	function getInsertDate() {
		return $this->__get(Runner::BHAA_RUNNER_INSERTDATE);
	}
	
	function getCompanyId() {
		return $this->__get('company');
		//return $this->__get(Runner::BHAA_RUNNER_COMPANY);
	}
	
	function getSectorTeamId() {
		return $this->__get('sectorteam');
	}
	
	/**
	 * Return a url link the runners company.
	 * @return string
	 */
	function getCompanyName($admin_url) {
		$cid = $this->getCompanyId();
		if(isset($cid)) {
			if($admin_url=="false"){
				return sprintf('<a href="%s">%s</a>',get_permalink($cid),get_the_title($cid));
			} else {
				return sprintf('<a href="%s">Edit %s</a>',get_edit_post_link($cid),get_the_title($cid));
			}
		} else {
			// no company
			return '';
		}
	}
	
	/**
	 * Return a url link to the sector team
	 * @param unknown $admin_url
	 * @return string
	 */
	function getSectorTeam($admin_url) {
		$id = $this->getSectorTeamId();
		if(isset($id)) {
			if($admin_url=="false"){
				return sprintf('<a href="%s">%s</a>',get_permalink($id),get_the_title($id));
			} else {
				return sprintf('<a href="%s">Edit %s</a>',get_edit_post_link($id),get_the_title($id));
			}
		} else {
			// no sector team
			return '';
		}
	}
	
	/**
	 * This method updates the runners reneal date and emails them a confirmation.
	 */
	function renew() {
		error_log('renew() '.$this->getID().' '.$this->getUserEmail());
		update_user_meta($this->getID(), Runner::BHAA_RUNNER_STATUS, 'M');
		update_user_meta($this->getID(), Runner::BHAA_RUNNER_DATEOFRENEWAL,date('Y-m-d'));
		$this->postMessageToRunner("Your BHAA Membership was ACTIVED.");
		if($this->getUserEmail()!=''||$this->getUserEmail()!=null) {
						
			global $wp_query;
			$wp_query->set('id',$this->getID());
			$page = get_page_by_title('email-runner-renewal');
			$message = apply_filters('the_content', $page->post_content);
			
			//Prepare headers for HTML
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset="utf-8"' . "\r\n";
			$headers .= 'From: Business Houses Athletic Association <info@bhaa.ie>' . "\r\n";
			$subject = 'BHAA Renewal 2017 : '.$this->getFirstName().' '.$this->getLastName();
 			$res = wp_mail($this->getUserEmail(),$subject,'<html>'.$message.'</html>',$headers);
 			error_log("renewal email sent ".$res);
		}
	}

	function deactivate() {
		update_user_meta($this->getID(),Runner::BHAA_RUNNER_STATUS,'I');
		$this->postMessageToRunner("Your BHAA Membership was marked as DEACTIVED.");
	}

	function postMessageToRunner($comment) {
		$data = array(
			'comment_author' => 'webmaster@bhaa.ie',
			'comment_author_email' => 'webmaster@bhaa.ie',
			//'comment_author_url' => 'http://www.catswhocode.com',
			'comment_content' => $comment,
			'comment_author_IP' => '127.0.0.1',
			//'comment_agent' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; fr; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3',
			'comment_date' => date('Y-m-d H:i:s'),
			'comment_date_gmt' => date('Y-m-d H:i:s'),
			'comment_approved' => 1,
			'user_id' => $this->getID()
		);
		//$comment_id = wp_insert_comment($data);
		//error_log("comment_id ".$comment_id);
	}
}
?>