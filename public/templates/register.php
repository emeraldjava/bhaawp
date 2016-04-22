<?php
/**
 * Template Name: BHAA Raceday Register
 * A template used to demonstrate how to include the template using this plugin.
 */
include_once 'header.php';
?>
<div class="jumbotron">
    <h1>BHAA Raceday Register</h1>
    <?php
    $reg = '<div class="navbar-search pull-left" align="left">
<input size="35" type="text" placeholder="Search BHAA Member by Name OR ID" id="memberfilter"/>
</div><br/><hr class="clear: both" />';
    $reg .= '<div class="container">';
    $reg .= '</div>';
    error_log('Here in the template page.');
    echo $reg;
    ?>
    <h2>this is some output.</h2>
</div>
<?php
include_once 'footer.php';
?>
