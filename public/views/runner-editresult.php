<?php
if ( !current_user_can( 'manage_options' ) )  {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
get_header();

echo "<pre>GET "; print_r($_GET); echo "</pre>";
echo "<pre>POST "; print_r($_POST); echo "</pre>";

wp_register_form('raceresult_form',array(new Raceresult_Form(),'build_form'));

echo wp_get_form('raceresult_form');
?>