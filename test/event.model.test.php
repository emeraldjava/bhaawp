<?php

define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once dirname(__FILE__).'./../bootstrap.php';

echo 'event model test';
$event = new EventModel(2123);
echo var_dump($event);

$races = $event->getRaces();
echo var_dump($races);
?>