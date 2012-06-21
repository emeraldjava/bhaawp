<?php
class CompanyAdmin
{
	function table()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>BHAA Comapany Admin Page.</p>';
		echo '</div>';
	}
}
?>