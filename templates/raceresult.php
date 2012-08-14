<table id="mylist" class="sortable">
    <thead>
        <tr>
            <th>Race</th>
            <th>Runner</th>
            <th>Time</th>
            <th>Number</th>
        </tr>
    </thead>
<tbody id="the-list">
<?php foreach ( $result AS $row ) : $class = ('alternate' == $class) ? '' : 'alternate'; ?>
<?php 
$url = get_permalink();
$url = add_query_arg('type', 'race', $url);
$url = add_query_arg('id', $row->race, $url);
?>
<tr class="<?php echo $class ?>">
	<td><a href="<?php echo $url; ?>"><?php echo $row->race ?></a></td>
	<td><?php echo $row->runner ?></td>
	<td><?php echo $row->racetime ?></td>
	<td><?php echo $row->number ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>