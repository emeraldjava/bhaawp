<?php
include_once 'raceday-header.php';

$list = $_REQUEST['racetec'];

echo '<table class="table table-bordered">
<tr>
<th>Number</th>
<th>BHAA</th>
<th>Name</th>
<th>Standard</th>
<th>Company</th>
</tr>';
foreach($list as $racetec) {
echo '<tr>';
echo '<td>'.$racetec->racenumber.'</td>';
echo '<td>'.$racetec->runner.'</td>';
echo '<td>'.$racetec->firstname.' '.$racetec->lastname.'</td>';
echo '<td>'.$racetec->standard.'</td>';
echo '<td>'.$racetec->companyname.'</td>';
echo '</tr>';
}
echo '</table>';
?>