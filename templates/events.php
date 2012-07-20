<table id="mylist" class="sortable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Tag</th>
            <th>Date</th>
            <th>Location</th>
        </tr>
    </thead>
<tbody id="the-list">
<?php foreach ( $events AS $event ) : $class = ('alternate' == $class) ? '' : 'alternate'; ?>
<?php 
$url = get_permalink();
$url       = add_query_arg('type', 'event', $url);
$url       = add_query_arg('id', $event->id, $url);
//$url       = add_query_arg('event_id', $event->id, $permalink);
//$url = "http://wplocal/bhaa_event=1";//add_query_arg('bhaa_event', $event->tag, get_permalink()); ?>
<tr class="<?php echo $class ?>">
	<td><a href="<?php echo $url; ?>"><?php echo $event->name ?></a></td>
	<td><?php echo $event->tag ?></td>
	<td><?php echo $event->date ?></td>
	<td><?php echo $event->location ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>