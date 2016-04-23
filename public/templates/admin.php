<?php
/**
 * Template Name: BHAA Raceday admin
 * A template used to demonstrate how to include the template using this plugin.
 */
include_once 'header.php';
?>

<?php
$event = Raceday::get_instance()->getEvent();
echo '<h2>BHAA RACE DAY ADMIN</h2>';
echo '<h3>Actions</h3>';
echo '<form action="'.admin_url( 'admin.php' ).'" method="POST">'.
		wp_nonce_field('bhaa_raceday_admin_import_prereg').'
	<input type="hidden" name="action" value="bhaa_raceday_admin_import_prereg"/>
	<input type="hidden" name="eventid" value="'.$event->post_id.'"/>
	<input type="hidden" name="raceid" value="'.$event->race.'"/>
	<input type="submit" value="Pre Reg Import"/>
	</form>';

//echo sprintf('<h3><a href="/raceday-admin?action=&=%d&=%d">Import PRE_REG</a></h3>',,);
//echo sprintf('<h3><a href="/raceday-admin?action=preregexport&eventid=%d&raceid=%d">Export PRE_REG</a></h3>',$event->event_id,$event->race);
//echo sprintf('<h3><a href="/raceday-admin?action=deleteall&eventid=%d&raceid=%d">Delete All RACE_REG</a></h3>',$event->event_id,$event->race);
?>
<?php
include_once 'footer.php';
?>
