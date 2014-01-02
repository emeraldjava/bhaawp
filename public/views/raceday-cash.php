<?php
$event = $_REQUEST['event'];//Registration::get_instance()->getEvent();
$runnerCount = $_REQUEST['runnerCount'];// Registration::get_instance()->getRegistrationTypes($event->race);
$registeredRunners = $_REQUEST['registeredRunners'];
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

$online=0;
$online_ro=0;
$online_bhaa=0;

//var_dump($runnerCount);

foreach($runnerCount as $runner) {
	error_log($runner->type.' '.$runner->count);
	switch($runner->type){
		case(1):{
			$member=$runner->count;
			$total = $total + ($member*10);
			$ro = $ro + ($member*10);
			break;
		}
		case(2):{
			$inactive_day=$runner->count;
			$total = $total + ($inactive_day*15);
			$ro = $ro + ($inactive_day*10);
			$bhaa = $bhaa + ($inactive_day*5);
			break;
		}
		case(3):{
			$renew=$runner->count;
			$total = $total + ($renew*25);
			$ro = $ro + ($renew*10);
			$bhaa = $bhaa + ($renew*15);
			break;
		}
		case(4):{
			$day=$runner->count;
			$total = $total + ($day*15);
			$ro = $ro + ($day*10);
			$bhaa = $bhaa + ($day*5);
			break;
		}
		case(5):{
			$new=$runner->count;
			$total = $total + ($new*25);
			$ro = $ro + ($new*10);
			$bhaa = $bhaa + ($new*15);
			break;
		}
		case(6):{
			$online_day=$runner->count;
			//$total = $total + ($online_day*10);
			$online = $online + ($online_day*15);
			$online_bhaa = $online_bhaa + ($online_day*5);
			$online_ro = $online_ro + ($online_day*10);
			break;
		}
		case(7):{
			$online_member=$runner->count;
			//error_log('online member '.$online_member);
			//$total = $total + ($online_member*15);
			$online = $online + ($online_member*10);
			$online_ro = $online_ro + ($online_member*10);
			break;
		}
	}
}

echo '<h1>BHAA '.$event->event_slug.' Cash</h1>';
echo '<table width=90%>';
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
echo '<td>1 BHAA Member</td>';
echo '<td>'.$member.'</td>';
echo '<td>10</td>';
echo '<td>10</td>';
echo '<td>0</td>';
echo '<td>'.($member*10).'</td>';
echo '<td>'.($member*10).'</td>';
echo '<td>0</td>';
echo '</tr>';

echo '<tr>';
echo '<td>2 Non-Renewing Member</td>';
echo '<td>'.$inactive_day.'</td>';
echo '<td>15</td>';
echo '<td>10</td>';
echo '<td>5</td>';
echo '<td>'.($inactive_day*15).'</td>';
echo '<td>'.($inactive_day*10).'</td>';
echo '<td>'.($inactive_day*5).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>3 Renewed Member</td>';
echo '<td>'.$renew.'</td>';
echo '<td>25</td>';
echo '<td>10</td>';
echo '<td>15</td>';
echo '<td>'.($renew*25).'</td>';
echo '<td>'.($renew*10).'</td>';
echo '<td>'.($renew*15).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>4 Day Member</td>';
echo '<td>'.$day.'</td>';
echo '<td>15</td>';
echo '<td>10</td>';
echo '<td>5</td>';
echo '<td>'.($day*15).'</td>';
echo '<td>'.($day*10).'</td>';
echo '<td>'.($day*5).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>5 New Member</td>';
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
echo '<td>6 Online Day Member</td>';
echo '<td>'.($online_day).'</td>';
echo '<td>15</td>';
echo '<td>10</td>';
echo '<td>5</td>';
echo '<td>'.($online_day*15).'</td>';
echo '<td>'.($online_day*10).'</td>';
echo '<td>'.($online_day*5).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>7 Online Member</td>';
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
echo '<td><h3>'.$online.'</h3></td>';
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
?>