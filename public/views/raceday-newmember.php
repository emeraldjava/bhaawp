<?php
include_once 'raceday-header.php';
echo '<div class="container">';
//echo "<pre>GET "; print_r($_GET); echo "</pre>";
//echo "<pre>POST "; print_r($_POST); echo "</pre>";
echo wp_get_form('daymemberform');
echo '</div>';
?>