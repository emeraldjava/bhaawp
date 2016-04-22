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
echo sprintf('<h3><a href="/raceday-admin?action=preregimport&eventid=%d&raceid=%d">Import PRE_REG</a></h3>',$event->event_id,$event->race);
echo sprintf('<h3><a href="/raceday-admin?action=preregexport&eventid=%d&raceid=%d">Export PRE_REG</a></h3>',$event->event_id,$event->race);//119,2851);
echo sprintf('<h3><a href="/raceday-admin?action=deleteall&eventid=%d&raceid=%d">Delete All RACE_REG</a></h3>',$event->event_id,$event->race);//119,2851);
?>
<?php
include_once 'footer.php';
?>