<?php 
$buttonMenu = '
<div class="btn-group btn-group-justified">
<a class="btn btn-primary" href="/raceday-register" role="button">BHAA Member</a>
<a class="btn btn-info" href="/raceday-newmember" role="button">New Member</a>
<a class="btn btn-success" href="/raceday-prereg" role="button">Pre-Reg</a>
<a class="btn btn-warning" href="/raceday-list" role="button">List</a>
<a class="btn btn-danger" href="/wp-admin/edit.php?post_type=race&action=bhaa-raceday-export" role="button">Export</a>
</div><hr/>
';
echo $buttonMenu;
?>