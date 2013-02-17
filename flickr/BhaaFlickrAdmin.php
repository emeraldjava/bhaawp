<?php
class BhaaFlickrAdmin
{
	function __construct()
	{
		add_action('admin_menu',array($this,'flickr_plus_menu'));
		add_action('admin_init',array($this,'flickr_plus_register_options'));
	}
	
	/**
	 * Register Options
	*/
	function flickr_plus_register_options() {
		register_setting( 'flickr_plus', 'flickr_plus_username' );
		register_setting( 'flickr_plus', 'flickr_plus_user_id' );
		register_setting( 'flickr_plus', 'flickr_plus_api_key' );
		register_setting( 'flickr_plus', 'flickr_plus_secret' );
	}
	
	function flickr_plus_menu() {
		add_options_page('BHAA Flickr', 'BHAA Flickr', 'manage_options', 'bhaa_flickr', array($this,'flickr_plus_adm'));
	}
	
	function flickr_plus_adm() {
		require_once('FlickrConfig.php');
	}
}
?>