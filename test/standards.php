<?php

require_once dirname(__FILE__).'./../bootstrap.php';

$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
echo $ONE->getKmPace(5);

?>