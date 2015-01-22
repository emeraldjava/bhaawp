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
	
	public $user;
	public $meta;
	
	public $user_data;
	protected $default = null;
	
	/**
	 * Construct a runner with a specific id
	 * @param unknown $id
	 * 
	 * http://codebyjeff.com/blog/2014/01/a-cleaner-wp-user-object
	 */
	function __construct($user_id) {
		$this->user = get_userdata($user_id);
		//$this->user = (array) get_user_by( 'id', $user_id )->data;
		
		//$user_meta = get_user_meta($user_id);//,"",false);
		$this->meta = @array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );
		$this->user_data = @array_merge($this->user, $this->user_meta);
		//var_dump($this->user_data);
	}

	function __get($var) {
		if (!array_key_exists($var, $this->user_data)){
			return $this->default;
		}
	    return $this->user_data[$var];		
	}
	
	function getID() {
		return $this->user->ID;
	}
	
	function geUserEmail() {
		return $this->user->user_email;
	}

	function getFirstName() {
		return $this->meta['first_name'];
	}
	
	function getLastName() {
		return $this->meta['last_name'];
	}
	
	function getFullName() {
		return $this->getFirstName().' '.$this->getLastName();
	}
	
	function getDateOfBirth() {
		return $this->meta[Runner::BHAA_RUNNER_DATEOFBIRTH];
	}
	
	function getStandard() {
		return $this->meta[Runner::BHAA_RUNNER_STANDARD];
	}
	
	function getGender() {
		return $this->meta[Runner::BHAA_RUNNER_GENDER];
	}
	
	function getStatus() {
		return $this->meta[Runner::BHAA_RUNNER_STATUS];
	}
	
	function getDateOfRenewal() {
		return $this->meta[Runner::BHAA_RUNNER_DATEOFRENEWAL];
	}
	
	function getInsertDate() {
		return $this->meta[Runner::BHAA_RUNNER_INSERTDATE];
	}
	
	function getCompany() {
		return $this->meta[Runner::BHAA_RUNNER_COMPANY];
	}
	
	function getMobile() {
		return $this->meta[Runner::BHAA_RUNNER_MOBILEPHONE];
	}
	
	/**
	 * Return a url link the runners company.
	 * @return string
	 */
	function getCompanyName() {
		$cid = $this->getCompany();
		if(isset($cid)) {
			return sprintf('<a href="%s">%s</a>',get_permalink($cid),get_the_title($cid));
		}		
		else
			return '';
	}
	
	/**
	 * This method updates the runners reneal date and emails them a confirmation.
	 */
	function renew() {
		error_log('renew() '.$this->user->ID.' '.$this->user->user_email);
		update_user_meta($this->user->ID, Runner::BHAA_RUNNER_STATUS, 'M');
		update_user_meta($this->user->ID, Runner::BHAA_RUNNER_DATEOFRENEWAL,date('Y-m-d'));
		if($this->user->user_email!=''||$this->user->user_email!=null) {
			
			//$runner = new Runner($EM_Booking->get_person()->ID);
			
			global $wp_query;
			$wp_query->set('id',$this->user->ID);
			
			//ob_start();
			//the_content();

			$page = get_page_by_title('email-runner-renewal');
			
			// http://digwp.com/2009/07/putting-the_content-into-a-php-variable/
			//ob_start();
			//$message = apply_filters('the_content', $this->get_the_content($page));
			//$message = ob_get_clean();
			
			$message = apply_filters('the_content', $page->post_content);
			//$content = ob_get_clean();
			
			//error_log($message);
			
			// runner-renewal-email
			/*$message = Bhaa_Mustache::get_instance()->loadTemplate('runner-renewal-email')->render(
				array(
					'user'=>$this,
					'sent_time'=>date('h:i:s d/m/Y', time())
				)
			);*/
			
			// email-runner-renewal
			//$css = bloginfo('stylesheet_url');
			//$css = get_stylesheet_uri().'/style.css';
			//error_log($css);
			//$message = Bhaa_Mustache::get_instance()->inlineCssStyles($message);
			//error_log($message);
			/*
			$inlineCss = true;
			if($inlineCss) {
				// create instance
				$cssToInlineStyles = new CssToInlineStyles();
				
				$html = $message;
				$css = file_get_contents('./templates/email.css');
				
				$cssToInlineStyles->setHTML($html);
				$cssToInlineStyles->setCSS($css);
				
				// output
				$message = $cssToInlineStyles->convert();
				//error_log($message);
			}	*/		
			
			//Prepare headers for HTML
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset="utf-8"' . "\r\n";
			$headers .= 'From: Business Houses Athletic Association <info@bhaa.ie>' . "\r\n";
			$subject = 'BHAA Renewal 2015 : '.$this->user->user_firstname.' '.$this->user->user_lastname;
 			$res = wp_mail($this->user->user_email,$subject,'<html>'.$message.'</html>',$headers);
 			error_log("email sent ".$res);
		}
		//wp_redirect(wp_get_referer());
		//exit();
	}
}
?>