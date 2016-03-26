<div class="wrap">
<h2>BHAA Registrar Page</h2>
Total number of registered runners:: TODO.
<table border="1">
  <tbody>
  <tr>
    <th>Month / Year</th>
    <th>Count</th>
  </tr>
<?php
foreach($results as $row) {
  echo sprintf('<tr><td>%s - %s</td><td>%d</td></tr>',
    $row->month,$row->year,$row->count);
}
?>
</tbody>
</table>
