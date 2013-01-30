<?php

define('SHORTINIT',true);
define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
//define('ABSPATH','C:\oconnellp\wamp\wordpress\');
require_once dirname(__FILE__).'./../bootstrap.php';

$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
//echo $ONE->getKmPace(5);

$standards = new StandardCalculator();
print_r($standards->standardTableSql);
print_r($standards->getTimeTable(5));
print_r($standards->getTimeTable(10));
?>