<?php
$event = $_REQUEST['event'];
$registeredRunners = $_REQUEST['registeredRunners'];

//var_dump($event);


/**
 * 1 - member - 10e
 * 2 - inactive day - 15e
 * 3 - renewing member - 25e
 * 4 - day member - 15e
 * 5 - new member - 25e
 * 6 - online day - 15e
 * 7 - online member - 10e
 */
$member=0;
$inactive_day=0;
$renew=0;
$day=0;
$new=0;
$online_day=0;
$online_member=0;

$total=0;
$ro=0;
$bhaa=0;

$online_total=0;
$online_ro=0;
$online_bhaa=0;

foreach($registeredRunners as $runner) {
	error_log($runner->standardscoringset.' '.$runner->firstname.' '.$runner->lastname);
	if($runner->standardscoringset==1) {
			$member++;
	} else if($runner->standardscoringset==2) {
			$inactive_day++;
	} else if($runner->standardscoringset==3) {
			$renew++;
	} else if($runner->standardscoringset==4) {
			$day++;
	} else if($runner->standardscoringset==5) {
			$new++;
	} else if($runner->standardscoringset==6) {
			$online_day++;
	} else if($runner->standardscoringset==7) {
			$online_member++;
	}
}
$total = ($member*10) + ($inactive_day*15) + ($renew*25) + ($day*15) + ($new*25);
$ro = ($member*10) + ($inactive_day*10) + ($renew*10) + ($day*10) + ($new*10);
$bhaa = ($inactive_day*5) + ($renew*15) + ($day*5) + ($new*15);

$online_total = ($online_day*15) + ($online_member*10);
$online_ro = ($online_day*10) + ($online_member*10);
$online_bhaa = ($online_day*5);

echo '<h1>BHAA '.strtoupper($event->event_slug).' Cash</h1>';
echo '<h2>'.sizeof($registeredRunners).' Total Runners</h2>';
echo '<table width=95%>';
echo '<tr align="left">';
echo '<th>Type</th>';
echo '<th>Number</th>';
echo '<th>Rate</th>';
echo '<th>RO</th>';
echo '<th>BHAA</th>';
echo '<th>Total</th>';
echo '<th>RO</th>';
echo '<th>BHAA</th>';
echo '</tr>';

echo '<tr>';
echo '<td>BHAA Member</td>';
echo '<td>'.$member.'</td>';
echo '<td>10</td>';
echo '<td>10</td>';
echo '<td>0</td>';
echo '<td>'.($member*10).'</td>';
echo '<td>'.($member*10).'</td>';
echo '<td>0</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Non-Renewing Member</td>';
echo '<td>'.$inactive_day.'</td>';
echo '<td>15</td>';
echo '<td>10</td>';
echo '<td>5</td>';
echo '<td>'.($inactive_day*15).'</td>';
echo '<td>'.($inactive_day*10).'</td>';
echo '<td>'.($inactive_day*5).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Renewed Member</td>';
echo '<td>'.$renew.'</td>';
echo '<td>25</td>';
echo '<td>10</td>';
echo '<td>15</td>';
echo '<td>'.($renew*25).'</td>';
echo '<td>'.($renew*10).'</td>';
echo '<td>'.($renew*15).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Day Member</td>';
echo '<td>'.$day.'</td>';
echo '<td>15</td>';
echo '<td>10</td>';
echo '<td>5</td>';
echo '<td>'.($day*15).'</td>';
echo '<td>'.($day*10).'</td>';
echo '<td>'.($day*5).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>New Member</td>';
echo '<td>'.$new.'</td>';
echo '<td>25</td>';
echo '<td>10</td>';
echo '<td>15</td>';
echo '<td>'.($new*25).'</td>';
echo '<td>'.($new*10).'</td>';
echo '<td>'.($new*15).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td><h3>Total Cash</h3></td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td><h3>'.($total).'</h3></td>';
echo '<td><h3>'.($ro).'</h3></td>';
echo '<td><h3>'.($bhaa).'</h3></td>';
echo '</tr>';

echo '<tr>';
echo '<td>Online Day Member</td>';
echo '<td>'.($online_day).'</td>';
echo '<td>15</td>';
echo '<td>10</td>';
echo '<td>5</td>';
echo '<td>'.($online_day*15).'</td>';
echo '<td>'.($online_day*10).'</td>';
echo '<td>'.($online_day*5).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Online Member</td>';
echo '<td>'.($online_member).'</td>';
echo '<td>10</td>';
echo '<td>10</td>';
echo '<td>0</td>';
echo '<td>'.($online_member*10).'</td>';
echo '<td>'.($online_member*10).'</td>';
echo '<td>'.($online_member*0).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td><h3>Total Online</h3></td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td><h3>'.$online_total.'</h3></td>';
echo '<td><h3>'.($online_ro).'</h3></td>';
echo '<td><h3>'.($online_bhaa).'</h3></td>';
echo '</tr>';

echo '<tr>';
echo '<td><h2>Total</h2></td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td> </td>';
echo '<td><h2>'.($ro+$online_ro).'</h2></td>';
echo '<td><h2>'.($bhaa-$online_ro).'</h2></td>';
echo '</tr>';

echo '</table>';

echo '<hr/>';

echo '<table id="raceteclist">
<tr class="row">
<th class="cell">ID</th>
<th class="cell">Name</th>
<th class="cell">Type</th>
</tr>';

foreach($registeredRunners as $registered) :
echo '<tr class="row">';
echo '<td class="cell">'.$registered->runner.'</td>';
echo '<td class="cell">'.$registered->firstname.' '.$registered->lastname.'</td>';
echo '<td class="cell">'.$registered->standardscoringset.'</td>';
echo '</tr>';
endforeach;
echo '</table>';
//var_dump($registeredRunners);
?>