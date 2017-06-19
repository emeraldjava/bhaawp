<?php
$link = admin_url('admin.php');
$race = get_the_title($_GET['id']);
echo '<div class="wrap"><h2>Edit Race Results : '.$race.'</h2>
    <form method="post" action="'.$link.'">
	<input type="hidden" name="action" value="bhaa_race_add_result" />
		<input type="hidden" name="post_id" value="'.$_GET['id'].'"/>
		<input type="submit" value="Add Race Result"/>
	</form>
    <hr/>';
echo Individual_Table::get_instance()->renderTable($_GET['id']).'</div>';
?>