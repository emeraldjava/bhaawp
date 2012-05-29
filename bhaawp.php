<?php
/*
Plugin Name: BHAA wordpress plugin
Plugin URI: https://github.com/emeraldjava/bhaawp
Description: Plugin to handle bhaa results
Version: 1.0.2
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/bhaawp
*/

$plugin_version = '1.0.2';

$plugin_name = 'bhaawp';
$plugin_file = $plugin_name.'.php';
$plugin_class = 'bhaa';
$plugin_admin_class = 'bhaaadmin';
$plugin_class_file = $plugin_name.'.class.php';
$plugin_admin_class_file = $plugin_name.'admin.class.php';
// define the plugin prefix we are going to use for naming all
// classes, ids, actions etc... this is done to avoid conflicts with other plugins
$plugin_prefix = $plugin_name.'_';
$plugin_dir = get_bloginfo('wpurl').'/wp-content/plugins/bhaawp';

// Include the class file
if (!class_exists($plugin_class)) {
	include('/home/assure/bhaa/wordpress/wp-content/plugins/bhaawp/'.$plugin_class_file);
	if (is_admin()) {
		require_once(dirname(__FILE__).'/'.$plugin_admin_class_file);
	}
}

//Create a new instance of the class file
if (class_exists($plugin_class)) {
	$bhaa_plugin = new $plugin_class();
}

//Create a new instance of the class file
if (is_admin() && class_exists($plugin_admin_class)) {
	$bhaa_admin_plugin = new $plugin_admin_class();
}

//Setup actions, hooks and filters
if(isset($bhaa_plugin)){

	// Activation function
	register_activation_hook(__FILE__, array(&$bhaa_plugin, 'activate'));

	/**
	 * Routing plugin actions to class file
	 */
	global $wp_query;

	add_shortcode('bhaa', array($bhaa_plugin, 'bhaa_shortcode'));

	if (is_admin()) {
		add_action('admin_menu', array($bhaa_admin_plugin, 'bhaa_admin_plugin_menu'));
	}
}
?>