<?php 
echo '<div class="wrap">';
echo '<h3>BHAA Members JSON<h3>';
echo '<h4>Last: '.get_option('bhaa_members_file_date').'. Latest: '.date("Y-m-d").'</h4>';
echo '<p><form action="'.get_permalink().'" id="bhaa_admin_members_json" method="post">
			<input type="hidden" name="command" value="bhaa_admin_members_json"/>
			<input type="Submit" value="Refresh Members"/>
		</form></p>';
echo '<hr/>';
echo $_REQUEST['content'];
echo '</div>';
?>