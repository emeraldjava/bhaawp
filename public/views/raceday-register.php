<?php 
include_once 'raceday-header.php';
echo 'BHAA Raceday Register';
echo '<div class="navbar-search pull-left" align="left">
<input size="35" type="text" placeholder="Search BHAA Member by Name OR ID" id="memberfilter"/>
</div><br/>';
echo wp_get_form('bhaa-register-form');
?>