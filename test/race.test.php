<?php

define('SHORTINIT',true);
define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once dirname(__FILE__).'./../bootstrap.php';

$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$wpdb->show_errors();


// 2362
$race = new Race();
$race->deleteResults(2362);
$race->loadResults(2362,'a,b,c
		1,2,3
		4,5,6');

?>