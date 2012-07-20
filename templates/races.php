<table id="mylist" class="sortable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Event</th>
            <th>Distance</th>
            <th>Unit</th>
        </tr>
    </thead>
<tbody id="the-list">
<?php foreach ( $result AS $row ) : $class = ('alternate' == $class) ? '' : 'alternate'; ?>
<?php 
$url = get_permalink();
$url       = add_query_arg('type', 'race', $url);
$url       = add_query_arg('id', $row->id, $url);
?>
<tr class="<?php echo $class ?>">
	<td><a href="<?php echo $url; ?>"><?php echo $row->id ?></a></td>
	<td><?php echo $row->event ?></td>
	<td><?php echo $row->distance ?></td>
	<td><?php echo $row->unit ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>