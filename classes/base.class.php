<?php
class Base
{
	function loadTemplate( $template, $vars = array() )
	{
		global $leaguemanager, $lmStats, $championship;
		extract($vars);
	
		ob_start();
		if ( file_exists( BHAAWP_PATH . '$template.php')) {
			include(BHAAWP_PATH . '$template.php');
		}
		elseif ( file_exists(BHAAWP_PATH . 'templates/'.$template.'.php') ) {
			include(BHAAWP_PATH . 'templates/'.$template.'.php');
		} 
		else 
		{
			parent::setMessage( sprintf(__('Could not load template %s.php', 'leaguemanager'), $template), true );
			parent::printMessage();
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	function checkTemplate( $template )
	{
		if ( file_exists( TEMPLATEPATH . "/leaguemanager/$template.php")) {
			return true;
		} elseif ( file_exists(BHAAWP_PATH . "/templates/".$template.".php") ) {
			return true;
		}
		return false;
	}
	
	function run_install_or_upgrade($table_name, $sql)//, $db_version)
	{
		global $wpdb;
	
		// Table does not exist, we create it!
		// We use InnoDB and UTF-8 by default
		if ($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name)
		{
			$create = "CREATE TABLE ".$table_name." ( ".$sql." ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	
			// We use the dbDelta method given by WP!
			require_once ABSPATH.'wp-admin/includes/upgrade.php';
			dbDelta($create);
		}
	}
}
?>