<?php

define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once( PLUGIN_ROOT_DIR.'/bootstrap.php');
//require_once dirname(__FILE__).'./../classes/Standard.class.php';
//require_once dirname(__FILE__).'./../classes/StandardCalculator.php';

$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
//print_r($ONE);
//print_r((array)$ONE);
//echo $ONE->getKmPace(5);

$standardCalc = new StandardCalculator();

print_r($standardCalc->getEventStandardTable(2123));

//print_r($standardCalc->standardTableSql);
//print_r(array_values((array)$standardCalc->standards));

//print_r($standardCalc->getTimeTable(3.2));

//print_r($standardCalc->standard_table(array('distance'=>3.2)));
//print_r($standards->getTimeTable(10));
?>