<?php 
include_once 'raceday-header.php';
echo '<div class="navbar-search pull-left" align="left">
<input size="35" type="text" placeholder="Search BHAA Member by Name OR ID" id="memberfilter"/>
</div><br/><hr class="clear: both" />';
echo '<div class="container">';
echo wp_get_form('registerform');
echo '</div>';
?>