<?php

define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once( WP_INSTALL_DIR . '/wp-content/plugins/events-manager/events-manager.php' );
require_once( PLUGIN_ROOT_DIR.'/bootstrap.php');

echo 'eventmanager test';

$EM_Booking = new EM_Booking(302);
//$EM_Booking->get_post(true);
//$EM_Booking->get_tickets();
//var_dump($EM_Booking->tickets);

foreach($EM_Booking->get_tickets()->tickets as $EM_Ticket)
	var_dump($EM_Ticket->ticket_name);

?>