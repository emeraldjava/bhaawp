<?php
//define('SHORTINIT',true);
define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once dirname(__FILE__).'./../bootstrap.php';

//$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
//$wpdb->show_errors();
echo 'League Model Test';
//$league = new LeagueModel(1);
//echo $league->getEvents();
?>