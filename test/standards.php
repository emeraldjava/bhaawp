<?php

require_once dirname(__FILE__).'./../classes/Standard.class.php';
require_once dirname(__FILE__).'./../classes/StandardCalculator.php';

$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
print_r($ONE);
print_r((array)$ONE);
//echo $ONE->getKmPace(5);

$standardCalc = new StandardCalculator();
print_r($standardCalc->standardTableSql);
print_r(array_values((array)$standardCalc->standards));
//print_r($standards->getTimeTable(5));
//print_r($standards->getTimeTable(10));
?>