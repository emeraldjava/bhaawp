<?php
/**
 * Template Name: BHAA Raceday New Member
 * A template used to demonstrate how to include the template using this plugin.
 */
include_once 'header.php';

//echo "<pre>GET "; print_r($_GET); echo "</pre>";
//echo "<pre>POST "; print_r($_POST); echo "</pre>";
?>
<div class="panel-heading">
    <h3 class="panel-title">New or Day Runner</h3>
</div>

<div class="panel-body">
<?php echo wp_get_form('daymemberform'); ?>
</div>
<script type="text/javascript">
    // When the document is ready
    $(document).ready(function () {
        $('#bhaa_dateofbirth').datepicker({
            format: "dd-mm-yyyy"
        });
    });
</script>
<?php
include_once 'footer.php';
?>