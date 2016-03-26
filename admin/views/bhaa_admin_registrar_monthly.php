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
  $runner_url = sprintf('<a target=new href="/runner/?id=%d">%d</a>',
    $row->ID,$row->ID);
  echo sprintf('<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td></tr>',
    $i,$runner_url,$row->display_name,$row->dor);
}
?>
</tbody>
</table>
<hr/>
<?php
$link = add_query_arg(
    array(
        'page' => 'bhaa-admin-registrar-deactivate',
        'year' => $_GET['year'],
        'month' => $_GET['month']
    ),
    admin_url('admin.php')
);
echo sprintf('<a href="%s">%s</a>',$link,"Deactive Runners");
?>
