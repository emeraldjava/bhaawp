<?php
/**
 * Template Name: BHAA Raceday Admin
 */
if ( !current_user_can( 'manage_options' ) )  {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

get_header();

//echo "<pre>GET "; print_r($_GET); echo "</pre>";
//echo "<pre>POST "; print_r($_POST); echo "</pre>";

if(isset($_GET['action'])){
	$race = trim($_GET['raceid']);
	$event = trim($_GET['eventid']);
	
	global $wpdb;
	
	if($_GET['action']=='deleterunner') {
		$runner = trim($_GET['runner']);
		error_log("deleterunner ".$runner.' '.$race);
		Raceday::get_instance()->deleteRunner($runner,$race);
	} elseif($_GET['action']=='deleteall') {
		error_log("deleteall ".$event.' '.$race);
		$wpdb->query(
			$wpdb->prepare('delete from wp_bhaa_raceresult where class="RACE_REG" and race=%d',$race)
		);
	} elseif($_GET['action']=='preregimport') {
		error_log("preregimport ".$event.' '.$race);
		$wpdb->query(
			$wpdb->prepare('delete from wp_bhaa_raceresult where class="PRE_REG" and race=%d',$race)
		);
		$wpdb->query(
			$wpdb->prepare('insert into wp_bhaa_raceresult(race,runner,class)
				select %d,person_id,"PRE_REG"
				from wp_em_bookings 
				join wp_users on wp_users.id=wp_em_bookings.person_id
				where event_id=%d
				and booking_status=1
				order by display_name desc',$race,$event)		
		);

	} elseif($_GET['action']=='preregexport') {
		error_log("preregexport ".$event.' '.$race);
	}
}


include_once 'raceday-header.php';
//include_once 'page-raceday-header.php';

$event = Raceday::get_instance()->getEvent();
//var_dump($event);
$registeredRunners = Raceday::get_instance()->listRegisteredRunners();

echo '<h2>BHAA RACE DAY ADMIN</h2>';
echo '<h3>Actions</h3>';
echo sprintf('<h3><a href="/raceday-admin?action=preregimport&eventid=%d&raceid=%d">Import PRE_REG</a></h3>',$event->event_id,$event->race);
echo sprintf('<h3><a href="/raceday-admin?action=preregexport&eventid=%d&raceid=%d">Export PRE_REG</a></h3>',$event->event_id,$event->race);//119,2851);
echo sprintf('<h3><a href="/raceday-admin?action=deleteall&eventid=%d&raceid=%d">Delete All RACE_REG</a></h3>',$event->event_id,$event->race);//119,2851);
echo '<hr/>';

echo '<table class="table table-condensed">
<tr>
<th>Name</th>
<th>Number</th>
<th>DELETE</th>
</tr>';

foreach($registeredRunners as $registered) : ?>
<tr>
<td><?php echo $registered->firstname;?> <?php echo $registered->lastname;?></td>
<td><?php echo $registered->racenumber;?></td>
<td><?php echo sprintf('<a href="/raceday-admin?action=deleterunner&runner=%d&raceid=%d">%d</a>',$registered->runner,$registered->race,$registered->runner);?></td>
</tr>
<?php endforeach;?>
</table>
<?php 
get_footer(); 
?>