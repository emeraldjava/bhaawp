<?php

// http://www.stormyfrog.com/using-wpdb-outside-wordpress/
// http://www.stormyfrog.com/using-wpdb-outside-wordpress-revisited/
define('SHORTINIT',true);
define('WP_INSTALL_DIR',''); // C:\oconnellp\wamp\wordpress
require_once( WP_INSTALL_DIR.'\wp-load.php');

$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$wpdb->show_errors();
echo $wpdb->query($wpdb->prepare('select id,tag FROM wp_bhaa_import'),ARRAY_A);

?>