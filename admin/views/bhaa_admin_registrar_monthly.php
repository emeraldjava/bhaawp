<div class="wrap">
<h2>bhaa_admin_registrar_monthly</h2>
<h3><?php echo $_GET['monthname'].' '.$_GET['year']; ?></h3>
Total number of registered runners:: <?php echo sizeof($results); ?>.
<table border="1">
  <tbody>
  <tr>
    <th>ID</th>
    <th>BHAA ID</th>
    <th>Name</th>
    <th>Renewal Date</th>
  </tr>
<?php
$i=0;
foreach($results as $row) {
  $i++;
  echo sprintf('<tr><td>%d</td><td>%d</td><td>%s</td><td>%s</td></tr>',
    $i,$row->ID,$row->display_name,$row->dor);
}
?>
</tbody>
</table>
