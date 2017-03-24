<?php 
echo '<div class="wrap">';
echo '<p>BHAA Members JSON '.$date.'</p>';
echo '<p><form action="'.get_permalink().'" id="bhaa_admin_members_json" method="post">
			<input type="hidden" name="command" value="bhaa_admin_members_json"/>
			<input type="Submit" value="Refresh Members"/>
		</form></p>';
echo '<hr/>';
echo $_REQUEST['content'];
echo '</div>';
?>