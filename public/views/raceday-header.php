<?php 
$a = '<table style="width:100%">
<tr style="line-height: 1px; padding-bottom: 1em;">
<td style="width:33%"><h3><a role="button" class="btn btn-default" href="/raceday-register/">Register</a></h3></td>
<td style="width:33%"><h3><a role="button" class="btn btn-default" href="/raceday-newmember/">New/Day Member</a></h3></td>		
<td style="width:33%"><h3><a role="button" class="btn btn-default" href="/raceday-prereg/">Pre-Registered</a></h3></td>
</tr>
<tr style="line-height: 14px; padding-bottom: 1em;">
<td style="width:33%"><h4><a href="/raceday-list/">List</a></h4></td>
<td style="width:33%"><h4><a href="/raceday-latest/">Latest</a></h4></td>
<td style="width:33%">
<a href="/wp-admin/edit.php?post_type=race&action=bhaa-raceday-export">Export</a>
</h4></td>
</tr>
</table>
<hr/>';

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