<?php
/**
 * Template Name: BHAA Raceday Register
 * A template used to demonstrate how to include the template using this plugin.
 */
wp_register_script(
    'bhaa_members',
    plugins_url('/assets/js/bhaa_members.js',dirname(__FILE__)),
    array('jquery'),
    null,
    false
);
wp_enqueue_script('bhaa_members');//,false,array(),"poc_version",false);

wp_register_script(
    'bhaa-raceday',
    plugins_url('/assets/js/bhaa-raceday.js',dirname(__FILE__))
);
wp_enqueue_script('bhaa-raceday');

include_once 'header.php';
?>
<div class="panel-heading">
    <h3 class="panel-title">Register & Renewed BHAA Runners</h3>
</div>

<div class="panel-body">
    <div class="navbar-search pull-left" align="left">
        <input size="35" type="text" placeholder="Search BHAA Member by Name OR ID" id="memberfilter"/>
    </div><br/><hr class="clear: both" />';
    <div class="container">';
        <?php echo wp_get_form('registerform'); ?>
    </div>
</div>

<?php
include_once 'footer.php';
?>