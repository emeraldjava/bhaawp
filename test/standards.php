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

//print_r($standardCalc->getEventStandardTable(2123));

//print_r($standardCalc->getEventStandardTable(2));


$distances = array();

$distance = array();
$distance['km'] = 1;
$distance['title'] = '1km';
$distances[0]= $distance;

$distance = array();
$distance['km'] = 1.6;
$distance['title'] = '1M';
$distances[1]= $distance;

$distance = array();
$distance['km'] = 5;
$distance['title'] = '5km';
$distances[2]= $distance;

$distance = array();
$distance['km'] = 10;
$distance['title'] = '10km';
$distances[3]= $distance;

$distance = array();
$distance['km'] = 21.1;
$distance['title'] = 'Half';
$distances[4]= $distance;

$distance = array();
$distance['km'] = 42.2;
$distance['title'] = 'Marathon';
$distances[5]= $distance;

print_r($standardCalc->generateTableForDistances($distances));

//print_r($standardCalc->standardTableSql);
//print_r(array_values((array)$standardCalc->standards));

//print_r($standardCalc->getTimeTable(3.2));

//print_r($standardCalc->standard_table(array('distance'=>3.2)));
//print_r($standards->getTimeTable(10));
?>