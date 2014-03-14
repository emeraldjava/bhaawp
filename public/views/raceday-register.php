<?php 
include_once 'raceday-header.php';
$reg = '<div class="navbar-search pull-left" align="left">
<input size="35" type="text" placeholder="Search BHAA Member by Name OR ID" id="memberfilter"/>
</div><br/><hr class="clear: both" />';
$reg .= '<div class="container">';
$reg .= wp_get_form('registerform');
$reg .= '</div>';
echo $reg;
?>