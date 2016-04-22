<?php
/**
 * Template Name: BHAA Raceday Pre-Ref
 * A template used to demonstrate how to include the template using this plugin.
 */
include_once 'header.php';
?>
<div class="panel-heading">
    <h3 class="panel-title">Pre-Registered Runners</h3>
</div>

<div class="panel-body">
    <?php echo Raceday::get_instance()->renderPreRegisteredRunnerTable(); ?>
</div>

<?php
include_once 'footer.php';
?>