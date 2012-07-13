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
}
?>