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
wp_enqueue_script('bhaa_members');

wp_register_script(
    'bhaa-raceday',
    plugins_url('/assets/js/bhaa-raceday.js',dirname(__FILE__))
);
wp_enqueue_script('bhaa-raceday');

include_once 'header.php';
?>
<div class="panel-heading">
    <h3 class="panel-title">Register BHAA Members</h3>
</div>

<div class="panel-body">
    <div class="row">
        <div class="ui-widget input-group col-md-12">
            <input type="text" id="memberfilter" class="search-query form-control" placeholder="Search by Name or ID"/>
        </div>
    </div>
    <div class="row">
        <?php echo wp_get_form('registerform'); ?>
    </div>
</div>

<?php
include_once 'footer.php';
?>
