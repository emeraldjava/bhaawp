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
  $link = add_query_arg(
      array(
          'page' => 'bhaa-admin-registrar-monthly', // as defined in the hidden page
          'year' => $row->year,
          'month' => $row->month,
          'monthname' => $row->monthname
      ),
      admin_url('admin.php')
  );
  echo sprintf('<tr><td><a href="%s">%s - %d</a></td><td>%d</td></tr>',
    $link,$row->monthname,$row->year,$row->count);
}
?>
</tbody>
</table>
