<?php
// http://wordpress.stackexchange.com/questions/15304/how-to-change-the-default-registration-email-plugin-and-or-non-plugin
define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once( PLUGIN_ROOT_DIR.'/bootstrap.php');

echo "wp-mail";

$user = new WP_User(7713);

$user_login = stripslashes($user->user_login);
$user_email = stripslashes($user->user_email);

$message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

//@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);


$message  = __('Hi there,') . "\r\n\r\n";
$message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
$message .= wp_login_url() . "\r\n";
$message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
$message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
$message .= __('Adios!');

wp_mail('paul.oconnell@aegon.ie', sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);

echo "wp-mail done";
?>