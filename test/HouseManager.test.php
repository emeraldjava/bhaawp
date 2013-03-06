<?php
define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once( PLUGIN_ROOT_DIR.'/bootstrap.php');

$houseManager = new HouseManager();
echo "HouseManager";

$houses = $houseManager->getInactiveCompanies();
echo print_r($houses,true);

$names = array_map(function($val){return $val->post_name;}, $houses);
echo print_r($names,true);


//echo print_r($houseManager->getActiveCompanies(),true);
?>