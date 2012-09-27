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
 * -- show bhaa logo on the login/registration page
 * http://www.paulund.co.uk/change-wordpress-login-logo-without-a-plugin
 */
class Runner
{	
	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_DATEOFBIRTH = 'bhaa_runner_dateofbirth';
	const BHAA_RUNNER_COMPANY = 'bhaa_runner_company';
	const BHAA_RUNNER_NEWSLETTER = 'bhaa_runner_newsletter';
	const BHAA_RUNNER_TEXTALERT = 'bhaa_runner_textalert';
	const BHAA_RUNNER_MOBILEPHONE = 'bhaa_runner_mobilephone';
	const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	
	const BHAA_RUNNER_STATUS = 'bhaa_runner_status';
	const MEMBER = 'M';
	const INACTIVE = 'I';
	const DAY = 'D';
	
	const BHAA_RUNNER_GENDER = 'bhaa_runner_gender';
	const MAN = 'M';
	const WOMAN = 'W';
	
	function Runner()
	{
	}
}
?>