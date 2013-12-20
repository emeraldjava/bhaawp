<?php
include_once 'raceday-header.php';

$list = $_REQUEST['racetec'];

//echo '<h2>Total Runners '.sizeof($list).'</h2>';
echo '<table width="90%" id="raceteclist" >
<tr>
<th>Number</th>
<th>BHAA</th>
<th>Name</th>
<th>Standard</th>
<th>Company</th>
</tr>';

foreach($list as $racetec) {
echo '<tr class="row">';
echo '<td class="cell">'.$racetec->racenumber.'</td>';
echo '<td class="cell">'.$racetec->runner.'</td>';
echo '<td class="cell">'.$racetec->firstname.' '.$racetec->lastname.'</td>';
echo '<td class="cell">'.$racetec->standard.'</td>';
echo '<td class="cell">'.$racetec->companyname.'</td>';
echo '</tr>';
}
echo '</table>';
?>